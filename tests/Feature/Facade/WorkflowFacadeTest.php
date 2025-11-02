<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests\Feature\Facade;

use InvalidArgumentException;
use NickyMatthijssen\LaravelWorkflow\Facade\WorkflowFacade;
use NickyMatthijssen\LaravelWorkflow\Tests\Mock\TestModel;
use Symfony\Component\Workflow\WorkflowInterface;

it('retrieves the workflow if it exists', function () {
    $workflow = WorkflowFacade::get(new TestModel, 'test');

    expect($workflow)->toBeInstanceOf(WorkflowInterface::class);
});

// it('throws an exception if workflow does not exist', function () {
//    $this->expectException(InvalidArgumentException::class);
//
//    WorkflowFacade::get(new TestModel(), 'non-existing');
// });
