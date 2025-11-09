<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests\Feature\Concerns;

use ShinobiZero\LaravelWorkflow\Facade\WorkflowFacade;
use ShinobiZero\LaravelWorkflow\Tests\Mock\TestModel;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\WorkflowInterface;

it('can get a workflow through the trait', function (?string $workflowName) {
    $workflow = $this->createStub(WorkflowInterface::class);
    $mock = new Testmodel();

    WorkflowFacade::shouldReceive('get')
        ->once()
        ->with($mock, $workflowName)
        ->andReturn($workflow);

    expect($mock->getWorkflow($workflowName))->toBe($workflow);
})->with([
    'with a workflow name' => ['workflow'],
    'without a workflow name' => [null],
]);

it('can check if a transition is possible through the trait', function (?string $workflowName, bool $expectedResult) {
    $mock = new Testmodel();

    WorkflowFacade::shouldReceive('can')
        ->once()
        ->with($mock, 'transition', $workflowName)
        ->andReturn($expectedResult);

    expect($mock->canTransitionTo('transition', $workflowName))->toEqual($expectedResult);
})->with([
    'with a workflow name and it can transition' => ['workflow', true],
    'with a workflow name and it cannot transition' => ['workflow', false],
    'without a workflow name and it can transition' => [null, true],
    'without a workflow name and it cannot transition' => [null, false],
]);

it('can transition through the trait', function (?string $workflowName) {
    $mock = new Testmodel();
    $marking = $this->createStub(Marking::class);

    WorkflowFacade::shouldReceive('apply')
        ->once()
        ->with($mock, 'transition', $workflowName)
        ->andReturn($marking);

    expect($mock->applyTransition('transition', $workflowName))->toBe($marking);
})->with([
    'with a workflow name' => ['workflow'],
    'without a workflow name' => [null],
]);
