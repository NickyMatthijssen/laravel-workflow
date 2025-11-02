<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow;

use Illuminate\Support\ServiceProvider;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactory;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;

final class WorkflowServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisteredWorkflowFactoryInterface::class => RegisteredWorkflowFactory::class,
    ];

    public array $singletons = [
        WorkflowManagerInterface::class => WorkflowManager::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/workflow.php', 'workflow');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/workflow.php' => config_path('workflow.php'),
        ]);
    }
}
