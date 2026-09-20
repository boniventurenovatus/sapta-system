<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Models\LeaveRequest;
use App\Models\UserActivityLog;
use App\Notifications\LeaveRequestSubmitted;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestReturned;
use App\Models\User;
use App\Models\Submission;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::query()->with(['employee', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('request_number', 'like', "%{$request->search}%")
                  ->orWhere('reason', 'like', "%{$request->search}%");
            });
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'    => LeaveRequest::count(),
            'pending'    => LeaveRequest::where('status', 'pending')->count(),
            'pending'  => LeaveRequest::whereIn('status', ['pending', 'pending'])->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
        ];

        return view('leave-requests.index', compact('leaves', 'stats'));
    }

    public function create()
    {
        $employees = Employee::take(50)->get();
        return view('leave-requests.create', compact('employees'));
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $action = $request->input('action', 'pending');

        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'leave_type'  => 'required|in:annual,sick,maternity,paternity,study,other',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:2000',
        ]);

        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $validated['total_days'] = $start->diff($end)->days + 1;
        $validated['status'] = $action === 'submit' ? 'pending' : 'pending';

        $leave = LeaveRequest::create($validated);

        AuditLog::create([
            'auditable_type'  => LeaveRequest::class,
            'auditable_id'    => $leave->id,
            'user_id'         => Auth::id(),
            'action'          => $action === 'submit' ? 'pending' : 'created',
            'previous_status' => null,
            'new_status'      => $leave->status,
            'comment'         => $action === 'submit' ? 'Leave request submitted' : 'Leave request saved as draft',
        ]);

        if ($action === 'submit') {
            $this->workflow->submit(
                formType: 'leave_request',
                title: 'Leave Request LV-' . str_pad($leave->id, 5, '0', STR_PAD_LEFT),
                data: $leave->toArray(),
                submittableType: LeaveRequest::class,
                submittableId: $leave->id
            );

            return redirect()->route('leave-requests.show', $leave->id)
                ->with('success', 'Leave Request submitted successfully!');
        }

        return redirect()->route('leave-requests.show', $leave->id)
            ->with('success', 'Leave Request saved as draft!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $submission = Submission::where('submittable_type', LeaveRequest::class)
            ->where('submittable_id', $leaveRequest->id)
            ->first();

        $versions = $submission ? $submission->versions : collect();
        $auditLogs = AuditLog::where('auditable_type', LeaveRequest::class)
            ->where('auditable_id', $leaveRequest->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('leave-requests.show', compact('leaveRequest', 'versions', 'auditLogs'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        if (!in_array($leaveRequest->status, ['pending', 'rejected'])) {
            return redirect()->route('leave-requests.show', $leaveRequest->id)
                ->with('error', 'Only drafts and returned requests can be edited.');
        }

        $employees = Employee::take(50)->get();
        return view('leave-requests.edit', compact('leaveRequest', 'employees'));
    }

    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        $action = $request->input('action', 'pending');

        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'leave_type'  => 'required|in:annual,sick,maternity,paternity,study,other',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:2000',
        ]);

        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $validated['total_days'] = $start->diff($end)->days + 1;

        if ($action === 'submit') {
            $validated['status'] = 'pending';

            $submission = Submission::where('submittable_type', LeaveRequest::class)
                ->where('submittable_id', $leaveRequest->id)
                ->first();

            if ($submission && $leaveRequest->status === 'rejected') {
                $this->workflow->resubmit($submission, $validated);
            } elseif (!$submission) {
                $this->workflow->submit(
                    formType: 'leave_request',
                    title: 'Leave Request ' . $leaveRequest->request_number,
                    data: $validated,
                    submittableType: LeaveRequest::class,
                    submittableId: $leaveRequest->id
                );
            }
        }

        $leaveRequest->update($validated);

        // Tuma notification kwa HR, Manager, CEO
        if ($action === 'submit') {
            $hrUsers = User::where('account_status', 'active')
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['hr_manager', 'hr_officer', 'ceo', 'manager', 'super_admin', 'admin']);
                })
                ->get();

            foreach ($hrUsers as $hr) {
                $hr->notify(new LeaveRequestSubmitted($leaveRequest));
            }
        }

        return redirect()->route('leave-requests.show', $leaveRequest->id)
            ->with('success', 'Leave Request updated successfully!');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $submission = Submission::where('submittable_type', LeaveRequest::class)
            ->where('submittable_id', $leaveRequest->id)
            ->first();

        if ($submission) {
            $this->workflow->approve($submission, $request->input('comment'));
        }

        // Tuma notification kwa employee
        $employeeUser = $leaveRequest->employee?->user;
        if ($employeeUser) {
            $employeeUser->notify(new LeaveRequestApproved($leaveRequest));

        UserActivityLog::log(
            action: 'approve',
            module: 'leave',
            description: 'Approved leave: ' . ($leaveRequest->employee->full_name ?? 'Employee'),
            subjectId: $leaveRequest->id,
            subjectType: 'App\Models\LeaveRequest'
        );
        }

        return redirect()->route('leave-requests.show', $leaveRequest->id)
            ->with('success', 'Leave Request approved successfully!');
    }

    public function returnRequest(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $submission = Submission::where('submittable_type', LeaveRequest::class)
            ->where('submittable_id', $leaveRequest->id)
            ->first();

        if ($submission) {
            $this->workflow->returnForCorrection($submission, $request->reason);
        }

        // Tuma notification kwa employee
        $employeeUser = $leaveRequest->employee?->user;
        if ($employeeUser) {
            $employeeUser->notify(new LeaveRequestReturned($leaveRequest, $request->reason));

        UserActivityLog::log(
            action: 'return',
            module: 'leave',
            description: 'Returned leave: ' . ($leaveRequest->employee->full_name ?? 'Employee'),
            subjectId: $leaveRequest->id,
            subjectType: 'App\Models\LeaveRequest',
            properties: ['reason' => $request->reason]
        );
        }

        return redirect()->route('leave-requests.show', $leaveRequest->id)
            ->with('success', 'Leave Request returned for correction.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave Request deleted successfully.');
    }

    public function downloadPdf(LeaveRequest $leaveRequest)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF package haipo.');
        }

        $data = [
            'leave' => $leaveRequest,
            'employee' => $leaveRequest->employee,
            'creator' => $leaveRequest->creator,
            'approver' => $leaveRequest->approver,
            'generated_at' => now(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leave-requests.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Leave-Request-' . $leaveRequest->request_number . '.pdf');
    }

    public function print(LeaveRequest $leaveRequest)
    {
        $data = [
            'leave' => $leaveRequest,
            'employee' => $leaveRequest->employee,
            'creator' => $leaveRequest->creator,
            'approver' => $leaveRequest->approver,
            'generated_at' => now(),
        ];

        return view('leave-requests.print', $data);
    }
}