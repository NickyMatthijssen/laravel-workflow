<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow;

use Illuminate\Container\Attributes\Config;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Symfony\Component\Workflow\WorkflowInterface;

readonly class WorkflowManager implements WorkflowManagerInterface
{
    private Registry $registry;

    public function __construct(
        private RegisteredWorkflowFactoryInterface $registeredWorkflowFactory,
        #[Config('workflow')]
        array $configuration,
    ) {
        $this->registry = new Registry;

        $workflowDefinitions = $this->getRegisteredWorkflows($configuration);
        foreach ($workflowDefinitions as $name => $workflowConfiguration) {
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
        return $this->get($subject, $workflowName)->can($subject, $transitionName);
    }

    public function apply(object $subject, string $transitionName, ?string $workflowName = null): Marking
    {
        return $this->get($subject, $workflowName)->apply($subject, $transitionName);
    }

    private function getRegisteredWorkflows(array $configuration): array
    {
        return array_merge(
            $configuration['workflows'],
            ...array_map(static fn (string $path) => require $path, $configuration['workflow_paths']),
        );
    }
}
