<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests\Feature\Facade;

use InvalidArgumentException;
use NickyMatthijssen\LaravelWorkflow\Facade\WorkflowFacade;
use NickyMatthijssen\LaravelWorkflow\Tests\Mock\TestModel;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\WorkflowInterface;

it('retrieves the workflow if it exists', function () {
    expect(WorkflowFacade::get(new TestModel()))->toBeInstanceOf(WorkflowInterface::class);
});

it('throws an exception if workflow does not exist', function () {
    $this->expectException(InvalidArgumentException::class);

    WorkflowFacade::get(new TestModel(), 'non-existing');
});

test('if workflow can transition to another state', function (string $transitionName, bool $expectedResult) {
    expect(WorkflowFacade::can(new TestModel(['status' => 'planned']), $transitionName, 'test'))->toBe($expectedResult);
})->with([
    'possible transition' => ['to_finished', true],
    'impossible transition' => ['to_concept', false],
    'non-existing transition' => ['to_non_existing', false],
]);

test('if workflow can apply transition to another state', function () {
    $marking = WorkflowFacade::apply(new TestModel(['status' => 'planned']), 'to_finished', 'test');

    expect($marking)->toBeInstanceOf(Marking::class)
        ->and($marking->getPlaces())->toEqual(['finished' => 1]);
});

test('workflow throws exception if it cannot transition to another state', function () {
    WorkflowFacade::apply(new TestModel(['status' => 'concept']), 'to_finished', 'test');
})->throws(NotEnabledTransitionException::class, 'Cannot apply transition "to_finished" on workflow "test".');
