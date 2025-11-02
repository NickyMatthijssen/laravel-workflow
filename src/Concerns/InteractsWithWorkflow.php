<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Concerns;

use NickyMatthijssen\LaravelWorkflow\Facade\WorkflowFacade;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\WorkflowInterface;

trait InteractsWithWorkflow
{
    public function getWorkflow(?string $workflowName = null): WorkflowInterface
    {
        return WorkflowFacade::get($this, $workflowName);
    }

    public function canTransitionTo(string $transitionName, ?string $workflowName = null): bool
    {
        return WorkflowFacade::can($this, $transitionName, $workflowName);
    }

    public function applyTransition(string $transitionName, ?string $workflowName = null): Marking
    {
        return WorkflowFacade::apply($this, $transitionName, $workflowName);
    }
}
