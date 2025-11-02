<?php

declare(strict_types=1);

pest()->extend(NickyMatthijssen\LaravelWorkflow\Tests\TestCase::class)
    ->in('Feature')
    ->beforeEach(function () {
        config()->set('workflow.workflow_paths', [__DIR__.'/../config/workflow.testing.php']);
    });
