<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementRequest;
use App\Models\Submission;
use App\Models\AuditLog;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcurementRequestController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index(Request $request)
    {
        $query = ProcurementRequest::query()->with(['requester', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('request_number', 'like', "%{$request->search}%")
                  ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'     => ProcurementRequest::count(),
            'pending'   => ProcurementRequest::where('status', 'pending')->count(),
            'approved'  => ProcurementRequest::where('status', 'approved')->count(),
            'completed' => ProcurementRequest::where('status', 'completed')->count(),
        ];

        return view('procurement-requests.index', compact('requests', 'stats'));
    }

    public function create()
    {
        return view('procurement-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:2000',
            'category'       => 'required|string|max:100',
            'quantity'       => 'required|integer|min:1',
            'estimated_cost' => 'required|numeric|min:0.01',
            'priority'       => 'required|in:low,medium,high,critical',
            'required_date'  => 'required|date|after_or_equal:today',
        ]);

        $validated['request_number'] = ProcurementRequest::generateRequestNumber();
        $validated['requested_by'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['submitted_at'] = now();

        $procRequest = ProcurementRequest::create($validated);

        // Audit log
        AuditLog::create([
            'auditable_type'  => ProcurementRequest::class,
            'auditable_id'    => $procRequest->id,
            'user_id'         => Auth::id(),
            'action'          => 'submitted',
            'previous_status' => null,
            'new_status'      => 'pending',
            'comment'         => 'Procurement request submitted',
            'ip_address'      => $request->ip(),
            'user_agent'      => $request->userAgent(),
        ]);

        // Submit kwa workflow
        $this->workflow->submit(
            formType: 'procurement_request',
            title: 'Procurement Request ' . $procRequest->request_number . ' — ' . $procRequest->title,
            data: $procRequest->toArray(),
            submittableType: ProcurementRequest::class,
            submittableId: $procRequest->id
        );

        return redirect()->route('procurement-requests.show', $procRequest->id)
            ->with('success', 'Procurement Request ' . $procRequest->request_number . ' submitted successfully!');
    }

    public function show(ProcurementRequest $procurementRequest)
    {
        $submission = Submission::where('submittable_type', ProcurementRequest::class)
            ->where('submittable_id', $procurementRequest->id)
            ->first();

        $versions = $submission ? $submission->versions : collect();
        $auditLogs = AuditLog::where('auditable_type', ProcurementRequest::class)
            ->where('auditable_id', $procurementRequest->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('procurement-requests.show', compact('procurementRequest', 'versions', 'auditLogs'));
    }

    public function edit(ProcurementRequest $procurementRequest)
    {
        if ($procurementRequest->status !== 'pending') {
            return redirect()->route('procurement-requests.show', $procurementRequest->id)
                ->with('error', 'Only pending requests can be edited.');
        }

        return view('procurement-requests.edit', compact('procurementRequest'));
    }

    public function update(Request $request, ProcurementRequest $procurementRequest)
    {
        if ($procurementRequest->status !== 'pending') {
            return redirect()->route('procurement-requests.show', $procurementRequest->id)
                ->with('error', 'Only pending requests can be edited.');
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:2000',
            'category'       => 'required|string|max:100',
            'quantity'       => 'required|integer|min:1',
            'estimated_cost' => 'required|numeric|min:0.01',
            'priority'       => 'required|in:low,medium,high,critical',
            'required_date'  => 'required|date',
        ]);

        $procurementRequest->update($validated);

        return redirect()->route('procurement-requests.show', $procurementRequest->id)
            ->with('success', 'Procurement Request updated successfully!');
    }

    public function approve(Request $request, ProcurementRequest $procurementRequest)
    {
        $procurementRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $submission = Submission::where('submittable_type', ProcurementRequest::class)
            ->where('submittable_id', $procurementRequest->id)
            ->first();

        if ($submission) {
            $this->workflow->approve($submission, $request->input('comment'));
        }

        AuditLog::create([
            'auditable_type'  => ProcurementRequest::class,
            'auditable_id'    => $procurementRequest->id,
            'user_id'         => Auth::id(),
            'action'          => 'approved',
            'previous_status' => 'pending',
            'new_status'      => 'approved',
            'comment'         => $request->input('comment') ?? 'Request approved',
            'ip_address'      => $request->ip(),
        ]);

        return redirect()->route('procurement-requests.show', $procurementRequest->id)
            ->with('success', 'Procurement Request approved successfully!');
    }

    public function reject(Request $request, ProcurementRequest $procurementRequest)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $procurementRequest->update([
            'status' => 'rejected',
            'return_reason' => $request->reason,
        ]);

        $submission = Submission::where('submittable_type', ProcurementRequest::class)
            ->where('submittable_id', $procurementRequest->id)
            ->first();

        if ($submission) {
            $this->workflow->returnForCorrection($submission, $request->reason);
        }

        AuditLog::create([
            'auditable_type'  => ProcurementRequest::class,
            'auditable_id'    => $procurementRequest->id,
            'user_id'         => Auth::id(),
            'action'          => 'rejected',
            'previous_status' => 'pending',
            'new_status'      => 'rejected',
            'comment'         => $request->reason,
            'ip_address'      => $request->ip(),
        ]);

        return redirect()->route('procurement-requests.show', $procurementRequest->id)
            ->with('success', 'Procurement Request rejected.');
    }

    public function complete(ProcurementRequest $procurementRequest)
    {
        $procurementRequest->update(['status' => 'completed']);

        AuditLog::create([
            'auditable_type'  => ProcurementRequest::class,
            'auditable_id'    => $procurementRequest->id,
            'user_id'         => Auth::id(),
            'action'          => 'completed',
            'previous_status' => 'approved',
            'new_status'      => 'completed',
            'comment'         => 'Request marked as completed',
        ]);

        return redirect()->route('procurement-requests.show', $procurementRequest->id)
            ->with('success', 'Procurement Request marked as completed!');
    }

    public function destroy(ProcurementRequest $procurementRequest)
    {
        $procurementRequest->delete();
        return redirect()->route('procurement-requests.index')
            ->with('success', 'Procurement Request deleted successfully.');
    }

    public function downloadPdf(ProcurementRequest $procurementRequest)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF package haipo.');
        }

        $data = [
            'procRequest' => $procurementRequest,
            'requester' => $procurementRequest->requester,
            'approver' => $procurementRequest->approver,
            'generated_at' => now(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('procurement-requests.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Procurement-Request-' . $procurementRequest->request_number . '.pdf');
    }

    public function print(ProcurementRequest $procurementRequest)
    {
        $data = [
            'procRequest' => $procurementRequest,
            'requester' => $procurementRequest->requester,
            'approver' => $procurementRequest->approver,
            'generated_at' => now(),
        ];

        return view('procurement-requests.print', $data);
    }
}