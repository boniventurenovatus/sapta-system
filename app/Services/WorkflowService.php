<?php

namespace App\Services;

use App\Models\Draft;
use App\Models\Submission;
use App\Models\SubmissionVersion;
use App\Models\AuditLog;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    public function saveDraft(string $formType, string $title, array $data, $draftableType = null, $draftableId = null): Draft
    {
        $draft = Draft::create([
            'draftable_type' => $draftableType,
            'draftable_id'   => $draftableId,
            'form_type'      => $formType,
            'title'          => $title,
            'data'           => $data,
            'user_id'        => Auth::id(),
            'status'         => 'draft',
        ]);

        $this->logAudit($draft, 'created', null, 'draft', 'Draft saved');

        return $draft;
    }

    public function submit(string $formType, string $title, array $data, $submittableType = null, $submittableId = null, $draftId = null): Submission
    {
        return DB::transaction(function () use ($formType, $title, $data, $submittableType, $submittableId, $draftId) {
            $submissionNumber = $this->generateSubmissionNumber($formType);

            $submission = Submission::create([
                'submission_number' => $submissionNumber,
                'submittable_type'  => $submittableType,
                'submittable_id'    => $submittableId,
                'form_type'         => $formType,
                'title'             => $title,
                'data'              => $data,
                'user_id'           => Auth::id(),
                'status'            => 'submitted',
                'current_version'   => 1,
                'submitted_at'      => now(),
            ]);

            SubmissionVersion::create([
                'submission_id'  => $submission->id,
                'version_number' => 1,
                'data'           => $data,
                'created_by'     => Auth::id(),
                'action'         => 'created',
                'change_notes'   => 'Initial submission',
            ]);

            if ($draftId) {
                Draft::where('id', $draftId)->delete();
            }

            $this->logAudit($submission, 'submitted', 'draft', 'submitted', 'Form submitted');

            return $submission;
        });
    }

    public function approve(Submission $submission, ?string $comment = null): Submission
    {
        $submission->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        Approval::create([
            'submission_id' => $submission->id,
            'approver_id'   => Auth::id(),
            'action'        => 'approved',
            'comment'       => $comment,
            'acted_at'      => now(),
        ]);

        $this->logAudit($submission, 'approved', 'submitted', 'approved', $comment);

        return $submission;
    }

    public function returnForCorrection(Submission $submission, string $reason): Submission
    {
        $submission->update([
            'status'        => 'returned',
            'return_reason' => $reason,
        ]);

        Approval::create([
            'submission_id' => $submission->id,
            'approver_id'   => Auth::id(),
            'action'        => 'returned',
            'comment'       => $reason,
            'acted_at'      => now(),
        ]);

        $this->logAudit($submission, 'returned', 'submitted', 'returned', $reason);

        return $submission;
    }

    public function resubmit(Submission $submission, array $data, ?string $notes = null): Submission
    {
        return DB::transaction(function () use ($submission, $data, $notes) {
            $newVersion = $submission->current_version + 1;

            $submission->update([
                'data'            => $data,
                'status'          => 'submitted',
                'current_version' => $newVersion,
                'return_reason'   => null,
                'submitted_at'    => now(),
            ]);

            SubmissionVersion::create([
                'submission_id'  => $submission->id,
                'version_number' => $newVersion,
                'data'           => $data,
                'created_by'     => Auth::id(),
                'action'         => 'resubmitted',
                'change_notes'   => $notes ?? "Version {$newVersion} resubmitted",
            ]);

            $this->logAudit($submission, 'resubmitted', 'returned', 'submitted', $notes);

            return $submission;
        });
    }

    public function getUserDrafts($userId = null)
    {
        return Draft::forUser($userId)->drafts()->orderBy('updated_at', 'desc')->get();
    }

    public function getUserSubmissions($userId = null)
    {
        return Submission::forUser($userId)->orderBy('created_at', 'desc')->get();
    }

    public function getPendingApprovals($userId = null)
    {
        return Approval::where('approver_id', $userId ?? Auth::id())
            ->where('action', 'pending')
            ->with('submission')
            ->get();
    }

    protected function generateSubmissionNumber(string $formType): string
    {
        $prefix = strtoupper(substr($formType, 0, 2));
        $count = Submission::where('form_type', $formType)->count() + 1;
        return $prefix . '-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    protected function logAudit($model, string $action, ?string $previousStatus, ?string $newStatus, ?string $comment = null): void
    {
        AuditLog::create([
            'auditable_type'  => get_class($model),
            'auditable_id'    => $model->id,
            'user_id'         => Auth::id(),
            'action'          => $action,
            'previous_status' => $previousStatus,
            'new_status'      => $newStatus,
            'comment'         => $comment,
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
    }
}