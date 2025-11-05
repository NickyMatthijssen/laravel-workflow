<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow;

use Illuminate\Support\ServiceProvider;
use NickyMatthijssen\LaravelWorkflow\Exceptions\WorkflowConfigurationNotFound;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactory;
use NickyMatthijssen\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;
use NickyMatthijssen\LaravelWorkflow\Service\ConfigurationWorkflowLoader;
use NickyMatthijssen\LaravelWorkflow\Service\WorkflowLoaderInterface;
use NickyMatthijssen\LaravelWorkflow\Service\WorkflowManager;
use NickyMatthijssen\LaravelWorkflow\Service\WorkflowManagerInterface;
use Symfony\Component\Workflow\Registry;

final class WorkflowServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisteredWorkflowFactoryInterface::class => RegisteredWorkflowFactory::class,
        WorkflowLoaderInterface::class => ConfigurationWorkflowLoader::class,
    ];

    public array $singletons = [
        Registry::class => Registry::class,
        WorkflowManagerInterface::class => WorkflowManager::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(sprintf('%s/../config/workflow.php', __DIR__), 'workflow');

        $workflowPaths = $this->app->make('config')->get('workflow.workflow_paths');
        foreach ($workflowPaths as $workflowPath) {
            if (! file_exists($workflowPath)) {
                throw new WorkflowConfigurationNotFound($workflowPath);
            }

            $this->mergeConfigFrom($workflowPath, 'workflow.workflows');
        }
    }

    public function boot(): void
    {
        $this->publishes([
            sprintf('%s/../config/workflow.php', __DIR__) => config_path('workflow.php'),
        ], 'workflow-config');
    }
}
