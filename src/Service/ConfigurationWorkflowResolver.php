<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Service;

use Illuminate\Container\Attributes\Config;

/**
 * TODO: A bit better naming for this resolver, it sounds like it's for resolving the workflows, but it is for resolving the configurations.
 * Made because some people might want to resolve their configuration in a different way but without rewriting the WorkflowManager.
 * For instance through directly php configuration or something they build themselves.
 */
final readonly class ConfigurationWorkflowResolver implements WorkflowResolverInterface
{
    public function __construct(
        #[Config('workflow')]
        private array $configuration,
    ) {}

    public function resolve(): array
    {
        return array_merge(
            $this->configuration['workflows'],
            ...array_map(static fn (string $path) => require $path, $this->configuration['workflow_paths'] ?? []),
        );
    }
}
