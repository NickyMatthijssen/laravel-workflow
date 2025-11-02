<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow;

use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;
use NickyMatthijssen\LaravelWorkflow\Service\WorkflowResolverInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Symfony\Component\Workflow\WorkflowInterface;

readonly class WorkflowManager implements WorkflowManagerInterface
{
    private Registry $registry;

    public function __construct(
        private RegisteredWorkflowFactoryInterface $registeredWorkflowFactory,
        private WorkflowResolverInterface $workflowResolver,
    ) {
        $this->registry = new Registry();

        foreach ($this->workflowResolver->resolve() as $name => $workflowConfiguration) {
            $registeredWorkflow = $this->registeredWorkflowFactory->fromWorkflowConfiguration($name, $workflowConfiguration);

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
}
