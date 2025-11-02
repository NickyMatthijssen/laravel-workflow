<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests;

use NickyMatthijssen\LaravelWorkflow\WorkflowServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            WorkflowServiceProvider::class,
        ];
    }
}
