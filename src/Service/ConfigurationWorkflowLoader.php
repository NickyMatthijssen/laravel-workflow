<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Service;

use Illuminate\Container\Attributes\Config;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;

final readonly class ConfigurationWorkflowLoader implements WorkflowLoaderInterface
{
    public function __construct(
        #[Config('workflow.workflows')]
        private array $workflowConfigurations,
        private RegisteredWorkflowFactoryInterface $registeredWorkflowFactory,
    ) {}

    public function load(): array
    {
        return collect($this->workflowConfigurations)
            ->map(
                fn (array $workflowConfiguration, string $workflowName) => $this->registeredWorkflowFactory->fromWorkflowConfiguration($workflowName, $workflowConfiguration),
            )
            ->values()
            ->toArray();
    }
}
