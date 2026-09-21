<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseClaim;
use App\Models\Submission;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseClaimController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index(Request $request)
    {
        $query = ExpenseClaim::query()->with(['employee', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('claim_number', 'like', "%{$request->search}%")
                  ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'    => ExpenseClaim::count(),
            'draft'    => ExpenseClaim::where('status', 'draft')->count(),
            'pending'  => ExpenseClaim::whereIn('status', ['submitted', 'pending_approval'])->count(),
            'approved' => ExpenseClaim::whereIn('status', ['approved', 'paid', 'completed'])->count(),
        ];

        return view('expense-claims.index', compact('claims', 'stats'));
    }

    public function create()
    {
        $employees = Employee::take(50)->get();
        $projects = DB::table('projects')->take(50)->get(['id', 'name']);
        $departments = DB::table('departments')->get(['id', 'name']);
        $regions = \App\Models\Region::orderBy('name')->get(['id', 'name']);
        $districts = \App\Models\District::orderBy('name')->get(['id', 'name', 'region_id']);
        $wards = \App\Models\Ward::orderBy('name')->get(['id', 'name', 'district_id']);
        return view('expense-claims.create', compact('employees', 'projects', 'departments', 'regions', 'districts', 'wards'));
    }

    public function store(Request $request)
    {
        $action = $request->input('action', 'draft');

        $validated = $request->validate([
            'employee_id'    => 'required|integer|exists:employees,id',
            'title'          => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:0.01',
            'currency'       => 'required|string|max:10',
            'expense_date'   => 'required|date',
            'description'    => 'required|string|max:2000',
            'project'        => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        $validated['claim_number'] = ExpenseClaim::generateClaimNumber();
        $validated['created_by'] = Auth::id();
        $validated['status'] = $action === 'submit' ? 'pending_approval' : 'draft';
        $validated['submitted_at'] = $action === 'submit' ? now() : null;

        $claim = ExpenseClaim::create($validated);

        AuditLog::create([
            'auditable_type'  => ExpenseClaim::class,
            'auditable_id'    => $claim->id,
            'user_id'         => Auth::id(),
            'action'          => $action === 'submit' ? 'submitted' : 'created',
            'previous_status' => null,
            'new_status'      => $claim->status,
            'comment'         => $action === 'submit' ? 'Expense claim submitted' : 'Expense claim saved as draft',
        ]);

        if ($action === 'submit') {
            $this->workflow->submit(
                formType: 'expense_claim',
                title: 'Expense Claim ' . $claim->claim_number . ' — ' . $claim->title,
                data: $claim->toArray(),
                submittableType: ExpenseClaim::class,
                submittableId: $claim->id
            );

            return redirect()->route('expense-claims.show', $claim->id)
                ->with('success', 'Expense Claim ' . $claim->claim_number . ' submitted successfully!');
        }

        return redirect()->route('expense-claims.show', $claim->id)
            ->with('success', 'Expense Claim ' . $claim->claim_number . ' saved as draft!');
    }

    public function show(ExpenseClaim $expenseClaim)
    {
        $submission = Submission::where('submittable_type', ExpenseClaim::class)
            ->where('submittable_id', $expenseClaim->id)
            ->first();

        $versions = $submission ? $submission->versions : collect();
        $auditLogs = AuditLog::where('auditable_type', ExpenseClaim::class)
            ->where('auditable_id', $expenseClaim->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('expense-claims.show', compact('expenseClaim', 'versions', 'auditLogs'));
    }

    public function edit(ExpenseClaim $expenseClaim)
    {
        if (!in_array($expenseClaim->status, ['draft', 'returned'])) {
            return redirect()->route('expense-claims.show', $expenseClaim->id)
                ->with('error', 'Only drafts and returned claims can be edited.');
        }

        $employees = Employee::take(50)->get();
        $projects = DB::table('projects')->take(50)->get(['id', 'name']);
        $departments = DB::table('departments')->get(['id', 'name']);
        return view('expense-claims.edit', compact('expenseClaim', 'employees', 'projects', 'departments'));
    }

    public function update(Request $request, ExpenseClaim $expenseClaim)
    {
        $action = $request->input('action', 'draft');

        $validated = $request->validate([
            'employee_id'    => 'required|integer|exists:employees,id',
            'title'          => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:0.01',
            'currency'       => 'required|string|max:10',
            'expense_date'   => 'required|date',
            'description'    => 'required|string|max:2000',
            'project'        => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        if ($action === 'submit') {
            $validated['status'] = 'pending_approval';
            $validated['submitted_at'] = now();

            $submission = Submission::where('submittable_type', ExpenseClaim::class)
                ->where('submittable_id', $expenseClaim->id)
                ->first();

            if ($submission && $expenseClaim->status === 'returned') {
                $this->workflow->resubmit($submission, $validated);
            } elseif (!$submission) {
                $this->workflow->submit(
                    formType: 'expense_claim',
                    title: 'Expense Claim ' . $expenseClaim->claim_number,
                    data: $validated,
                    submittableType: ExpenseClaim::class,
                    submittableId: $expenseClaim->id
                );
            }
        }

        $expenseClaim->update($validated);

        return redirect()->route('expense-claims.show', $expenseClaim->id)
            ->with('success', 'Expense Claim updated successfully!');
    }

    public function approve(Request $request, ExpenseClaim $expenseClaim)
    {
        $expenseClaim->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $submission = Submission::where('submittable_type', ExpenseClaim::class)
            ->where('submittable_id', $expenseClaim->id)
            ->first();

        if ($submission) {
            $this->workflow->approve($submission, $request->input('comment'));
        }

        return redirect()->route('expense-claims.show', $expenseClaim->id)
            ->with('success', 'Expense Claim approved successfully!');
    }

    public function returnClaim(Request $request, ExpenseClaim $expenseClaim)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $expenseClaim->update([
            'status' => 'returned',
            'return_reason' => $request->reason,
        ]);

        $submission = Submission::where('submittable_type', ExpenseClaim::class)
            ->where('submittable_id', $expenseClaim->id)
            ->first();

        if ($submission) {
            $this->workflow->returnForCorrection($submission, $request->reason);
        }

        return redirect()->route('expense-claims.show', $expenseClaim->id)
            ->with('success', 'Expense Claim returned for correction.');
    }

    public function markPaid(ExpenseClaim $expenseClaim)
    {
        $expenseClaim->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('expense-claims.show', $expenseClaim->id)
            ->with('success', 'Expense Claim marked as paid!');
    }

    public function destroy(ExpenseClaim $expenseClaim)
    {
        $expenseClaim->delete();
        return redirect()->route('expense-claims.index')
            ->with('success', 'Expense Claim deleted successfully.');
    }

    public function downloadPdf(ExpenseClaim $expenseClaim)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF package haipo.');
        }

        $data = [
            'claim' => $expenseClaim,
            'employee' => $expenseClaim->employee,
            'creator' => $expenseClaim->creator,
            'approver' => $expenseClaim->approver,
            'generated_at' => now(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('expense-claims.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Expense-Claim-' . $expenseClaim->claim_number . '.pdf');
    }

    public function print(ExpenseClaim $expenseClaim)
    {
        $data = [
            'claim' => $expenseClaim,
            'employee' => $expenseClaim->employee,
            'creator' => $expenseClaim->creator,
            'approver' => $expenseClaim->approver,
            'generated_at' => now(),
        ];

        return view('expense-claims.print', $data);
    }
}