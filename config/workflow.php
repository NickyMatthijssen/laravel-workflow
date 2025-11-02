<?php

declare(strict_types=1);

use NickyMatthijssen\LaravelWorkflow\Enums\Type;
use NickyMatthijssen\LaravelWorkflow\MarkingStores\EloquentMarkingStore;

return [
    /**
     * The default marking store to use for all workflows.
     * This can be overridden on a per-workflow basis.
     * If you want to rename the default marking store, you can do so here.
     */
    'default_marking_store' => EloquentMarkingStore::class,

    /**
     * The default property to use for all workflows.
     * Useful if every workflow subject uses the same property to store the marking.
     * This way it is not necessary to specify the property on every workflow.
     */
    'default_property' => 'marking',

    /**
     * In case you want to use multiple workflow files, you can add them here, this could be useful if you want to use modules or split it up by feature.
     * The configurations should be in the same format as the 'workflows' key in this file.
     */
    'workflow_paths' => [
        __DIR__.'/workflow.testing.php',
        //         base_path('config/workflows.php'),
    ],

    /**
     * Workflow definitions.
     */
    'workflows' => [
        //        'test' => [
        //            'type' => Type::StateMachine,
        //            'supports' => [stdClass::class],
        //            'initial_marking' => 'draft',
        //            'places' => [
        //                'concept',
        //                'planned',
        //                'cancelled',
        //                'finished',
        //            ],
        //            'transitions' => [
        //                'to_planned' => ['from' => 'concept', 'to' => 'planned'],
        //                'to_cancelled_from_concept' => ['from' => 'concept', 'to' => 'cancelled'],
        //                'to_cancelled_from_planned' => ['from' => 'planned', 'to' => 'cancelled'],
        //                'to_finished' => ['from' => 'planned', 'to' => 'finished'],
        //            ],
        //            'marking_store' => \Symfony\Component\Workflow\MarkingStore\MethodMarkingStore::class,
        //            'property' => 'status',
        //        ],
    ],
];
