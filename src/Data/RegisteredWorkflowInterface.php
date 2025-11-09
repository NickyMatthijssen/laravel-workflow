<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Data;

use ShinobiZero\LaravelWorkflow\Enums\Type;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\WorkflowInterface;

interface RegisteredWorkflowInterface
{
    public function getType(): Type;

    public function getInitialPlace(): string;

    /**
     * @return list<class-string>
     */
    public function getSupports(): array;

    /**
     * @return list<string>
     */
    public function getPlaces(): array;

    /**
     * @return list<Transition>
     */
    public function getTransitions(): array;

    public function toWorkflow(): WorkflowInterface;
}
