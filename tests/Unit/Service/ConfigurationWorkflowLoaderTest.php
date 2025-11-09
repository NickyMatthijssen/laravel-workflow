<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests\Unit\Service;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflow;
use ShinobiZero\LaravelWorkflow\Enums\Type;
use ShinobiZero\LaravelWorkflow\Factory\RegisteredWorkflowFactoryInterface;
use ShinobiZero\LaravelWorkflow\MarkingStores\EloquentMarkingStore;
use ShinobiZero\LaravelWorkflow\Service\ConfigurationWorkflowLoader;
use ShinobiZero\LaravelWorkflow\Tests\Mock\TestModel;
use stdClass;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;

test('load will return a list of RegisteredWorkflow objects', function () {
    $configuration = [
        'type' => Type::StateMachine,
        'supports' => [TestModel::class],
        'initial_place' => 'concept',
        'places' => ['a', 'b'],
        'transitions' => ['a_to_b' => ['from' => 'a', 'to' => 'b']],
        'marking_store' => EloquentMarkingStore::class,
    ];

    $registeredWorkflow = new RegisteredWorkflow(
        'workflow',
        Type::StateMachine,
        'a',
        [stdClass::class],
        ['a', 'b'],
        [new Transition('a_to_b', 'a', 'b')],
        new MethodMarkingStore(true, 'marking'),
    );

    $registeredWorkflowFactory = $this->createMock(RegisteredWorkflowFactoryInterface::class);
    $registeredWorkflowFactory->expects($this->once())->method('fromWorkflowConfiguration')->with('test', $configuration)->willReturn($registeredWorkflow);

    $this->assertSame(
        (new ConfigurationWorkflowLoader(['test' => $configuration], $registeredWorkflowFactory))->load(),
        [$registeredWorkflow],
    );
});
