<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Service;

use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class WorkflowManager implements WorkflowManagerInterface
{
    private array $registeredWorkflows;

    public function __construct(private Registry $registry, WorkflowLoaderInterface $workflowLoader)
    {
        $this->registeredWorkflows = $workflowLoader->load();

        foreach ($this->registeredWorkflows as $registeredWorkflow) {
            foreach ($registeredWorkflow->getSupports() as $class) {
                $this->registry->addWorkflow($registeredWorkflow->toWorkflow(), new InstanceOfSupportStrategy($class));
            }
        }
    }

    public function get(object $subject, ?string $workflowName = null): ?WorkflowInterface
    {
        return $this->registry->get($subject, $workflowName);
    }

    public function can(object $subject, string $transitionName, ?string $workflowName = null): bool
    {
        return $this->registry->get($subject, $workflowName)->can($subject, $transitionName);
    }

    public function apply(object $subject, string $transitionName, ?string $workflowName = null): Marking
    {
        return $this->registry->get($subject, $workflowName)->apply($subject, $transitionName);
    }

    public function getRegisteredWorkflows(): array
    {
        return $this->registeredWorkflows;
    }
}
