<?php

declare(strict_types=1);

use NickyMatthijssen\LaravelWorkflow\Enums\Type;
use NickyMatthijssen\LaravelWorkflow\MarkingStores\EloquentMarkingStore;
use NickyMatthijssen\LaravelWorkflow\Tests\Mock\TestModel;

return [
    'test' => [
        'type' => Type::StateMachine,
        'supports' => [TestModel::class],
        'initial_place' => 'concept',
        'places' => [
            'concept',
            'planned',
            'cancelled',
            'finished',
        ],
        'transitions' => [
            'to_planned' => ['from' => 'concept', 'to' => 'planned'],
            'to_cancelled_from_concept' => ['from' => 'concept', 'to' => 'cancelled'],
            'to_cancelled_from_planned' => ['from' => 'planned', 'to' => 'cancelled'],
            'to_finished' => ['from' => 'planned', 'to' => 'finished'],
        ],
        'marking_store' => EloquentMarkingStore::class,
        'property' => 'status',
    ],
];
