<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Draft;
use App\Models\Submission;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Auth;

class DraftController extends Controller
{
    protected WorkflowService $workflow;

    public function __construct(WorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index()
    {
        $drafts = $this->workflow->getUserDrafts();
        return view('my-work.drafts', compact('drafts'));
    }

    public function show(Draft $draft)
    {
        if ($draft->user_id !== Auth::id()) {
            abort(403);
        }
        return view('drafts.show', compact('draft'));
    }

    public function edit(Draft $draft)
    {
        if ($draft->user_id !== Auth::id()) {
            abort(403);
        }
        return view('drafts.edit', compact('draft'));
    }

    public function destroy(Draft $draft)
    {
        if ($draft->user_id !== Auth::id()) {
            abort(403);
        }
        $draft->delete();
        return redirect()->route('my-work.drafts')->with('success', 'Draft deleted successfully.');
    }

    public function submitDraft(Draft $draft)
    {
        if ($draft->user_id !== Auth::id()) {
            abort(403);
        }

        $submission = $this->workflow->submit(
            formType: $draft->form_type,
            title: $draft->title,
            data: $draft->data,
            draftId: $draft->id
        );

        return redirect()->route('my-work.submissions')->with('success', 'Form submitted successfully: ' . $submission->submission_number);
    }
}