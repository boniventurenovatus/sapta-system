<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index()
    {
        $approvals = $this->workflow->getPendingApprovals();
        $pendingSubmissions = Submission::whereIn('status', ['submitted', 'pending_approval'])
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('my-work.approvals', compact('approvals', 'pendingSubmissions'));
    }

    public function show(Submission $submission)
    {
        $versions = $submission->versions;
        $auditLogs = $submission->auditLogs()->with('user')->orderBy('created_at', 'desc')->get();
        return view('my-work.approvals-show', compact('submission', 'versions', 'auditLogs'));
    }

    public function approve(Request $request, Submission $submission)
    {
        $request->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        $this->workflow->approve($submission, $request->comment);

        return redirect()->route('my-work.approvals')->with('success', 'Submission approved successfully.');
    }

    public function returnForCorrection(Request $request, Submission $submission)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $this->workflow->returnForCorrection($submission, $request->reason);

        return redirect()->route('my-work.approvals')->with('success', 'Submission returned for correction.');
    }
}