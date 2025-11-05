<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests\Unit\Data;

use NickyMatthijssen\LaravelWorkflow\Data\RegisteredWorkflow;
use NickyMatthijssen\LaravelWorkflow\Enums\Type;
use stdClass;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

it('can transform an instance of RegisteredWorkflow to a WorkflowInterface', function () {
    $methodMarkingStore = new MethodMarkingStore(true, 'marking');

    $workflow = (new RegisteredWorkflow(
        'workflow',
        Type::StateMachine,
        'a',
        [stdClass::class],
        ['a', 'b'],
        [new Transition('a_to_b', 'a', 'b')],
        $methodMarkingStore,
    ))->toWorkflow();

    expect($workflow)->toBeInstanceOf(Workflow::class)
        ->and($workflow->getName())->toBe('workflow')
        ->and($workflow->getMarkingStore())->toBe($methodMarkingStore)
        ->and($workflow->getDefinition()->getInitialPlaces())->toBe(['a'])
        ->and($workflow->getDefinition()->getPlaces())->toEqual(['a' => 'a', 'b' => 'b'])
        ->and(
            array_map(static fn (Transition $transition) => [
                $transition->getName(),
                $transition->getFroms(),
                $transition->getTos(),
            ], $workflow->getDefinition()->getTransitions()),
        )->toEqual([['a_to_b', ['a'], ['b']]]);
});
