<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests\Unit\Factory;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflow;
use ShinobiZero\LaravelWorkflow\Enums\Type;
use ShinobiZero\LaravelWorkflow\Exceptions\UnexpectedValueException;
use ShinobiZero\LaravelWorkflow\Factory\RegisteredWorkflowFactory;
use ShinobiZero\LaravelWorkflow\MarkingStores\EloquentMarkingStore;
use stdClass;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use ValueError;

it('can create a registered workflow from configuration', function (
    Type $type = Type::Workflow,
    ?string $markingStoreClass = null,
    ?string $property = null,
) {
    $configuration = [
        'type' => $type,
        'initial_place' => 'a',
        'places' => ['a', 'b'],
        'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
        'supports' => [stdClass::class],
    ];

    if (null !== $markingStoreClass) {
        $configuration['marking_store'] = $markingStoreClass;
    }

    $expectedProperty = $property ?? 'marking';
    if (null !== $property) {
        $configuration['property'] = $property;
    }

    expect(
        (new RegisteredWorkflowFactory(MethodMarkingStore::class, 'marking'))->fromWorkflowConfiguration('workflow', $configuration),
    )->toEqual(
        new RegisteredWorkflow(
            'workflow',
            $type,
            'a',
            [stdClass::class],
            ['a', 'b'],
            [new Transition('a_to_b', 'a', 'b')],
            match ($markingStoreClass ?? MethodMarkingStore::class) {
                MethodMarkingStore::class => new MethodMarkingStore(Type::StateMachine === $type, $expectedProperty),
                EloquentMarkingStore::class => new EloquentMarkingStore(Type::StateMachine === $type, $expectedProperty),
            },
        ),
    );
})->with([
    'type workflow' => [
        'type' => Type::Workflow,
    ],
    'type state machine' => [
        'type' => Type::StateMachine,
    ],
    'with a defined marking store' => [
        'markingStoreClass' => EloquentMarkingStore::class,
    ],
    'with defined property' => [
        'property' => 'status',
    ],
]);

test('fromWorkflowConfiguration throws if type is missing/null', function () {
    (new RegisteredWorkflowFactory(MethodMarkingStore::class, 'marking'))
        ->fromWorkflowConfiguration('workflow', [
            'initial_place' => 'a',
            'places' => ['a', 'b'],
            'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
            'supports' => [stdClass::class],
        ]);
})->throws(UnexpectedValueException::class);

test('fromWorkflowConfiguration throws if type invalid', function () {
    (new RegisteredWorkflowFactory(MethodMarkingStore::class, 'marking'))
        ->fromWorkflowConfiguration('workflow', [
            'type' => 'not_a_valid_type',
            'initial_place' => 'a',
            'places' => ['a', 'b'],
            'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
            'supports' => [stdClass::class],
        ]);
})->throws(ValueError::class);

test('fromWorkflowConfiguration throws if initial_place is missing/null', function () {
    (new RegisteredWorkflowFactory(MethodMarkingStore::class, 'marking'))
        ->fromWorkflowConfiguration('workflow', [
            'type' => Type::Workflow->value,
            'places' => ['a', 'b'],
            'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
            'supports' => [stdClass::class],
        ]);
})->throws(UnexpectedValueException::class);

test('fromWorkflowConfiguration throws if supports is missing/null', function () {
    (new RegisteredWorkflowFactory(MethodMarkingStore::class, 'marking'))
        ->fromWorkflowConfiguration('workflow', [
            'type' => Type::Workflow->value,
            'initial_place' => 'a',
            'places' => ['a', 'b'],
            'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
        ]);
})->throws(UnexpectedValueException::class);
