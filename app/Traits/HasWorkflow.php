<?php

namespace App\Traits;

use App\Services\WorkflowService;

trait HasWorkflow
{
    public function workflow(): WorkflowService
    {
        return app(WorkflowService::class);
    }

    public function saveAsDraft(array $data, string $title): void
    {
        $this->workflow()->saveDraft(
            formType: $this->getFormType(),
            title: $title,
            data: $data,
            draftableType: get_class($this),
            draftableId: $this->id
        );
    }

    public function submitForm(array $data, string $title)
    {
        return $this->workflow()->submit(
            formType: $this->getFormType(),
            title: $title,
            data: $data,
            submittableType: get_class($this),
            submittableId: $this->id
        );
    }

    abstract public function getFormType(): string;
}