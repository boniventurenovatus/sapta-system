<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index()
    {
        $submissions = $this->workflow->getUserSubmissions();
        return view('my-work.submissions', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }
        $versions = $submission->versions;
        $auditLogs = $submission->auditLogs()->with('user')->orderBy('created_at', 'desc')->get();
        return view('my-work.submissions-show', compact('submission', 'versions', 'auditLogs'));
    }
}