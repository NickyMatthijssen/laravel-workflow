<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow;

use Illuminate\Support\ServiceProvider;
use ShinobiZero\LaravelWorkflow\Exceptions\WorkflowConfigurationNotFound;
use ShinobiZero\LaravelWorkflow\Factory\RegisteredWorkflowFactory;
use ShinobiZero\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;
use ShinobiZero\LaravelWorkflow\Service\ConfigurationWorkflowLoader;
use ShinobiZero\LaravelWorkflow\Service\WorkflowLoaderInterface;
use ShinobiZero\LaravelWorkflow\Service\WorkflowManager;
use ShinobiZero\LaravelWorkflow\Service\WorkflowManagerInterface;
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
        ], 'laravel-workflow-config');
    }
}
