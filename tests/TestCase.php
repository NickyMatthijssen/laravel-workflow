<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ShinobiZero\LaravelWorkflow\WorkflowServiceProvider;

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
