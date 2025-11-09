<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Service;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflow;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\WorkflowInterface;

interface WorkflowManagerInterface
{
    public function get(object $subject, ?string $workflowName = null): ?WorkflowInterface;

    public function can(object $subject, string $transitionName, ?string $workflowName = null): bool;

    public function apply(object $subject, string $transitionName, ?string $workflowName = null): Marking;

    /**
     * @return list<RegisteredWorkflow>
     */
    public function getRegisteredWorkflows(): array;
}
