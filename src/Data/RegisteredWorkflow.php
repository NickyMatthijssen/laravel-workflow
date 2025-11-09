<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Data;

use ShinobiZero\LaravelWorkflow\Enums\Type;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class RegisteredWorkflow implements RegisteredWorkflowInterface
{
    public function __construct(
        private string $name,
        private Type $type,
        private string $initialPlace,
        private array $supports,
        private array $places,
        private array $transitions,
        private MarkingStoreInterface $markingStore,
    ) {}

    public function getType(): Type
    {
        return $this->type;
    }

    public function getInitialPlace(): string
    {
        return $this->initialPlace;
    }

    public function getSupports(): array
    {
        return $this->supports;
    }

    public function getPlaces(): array
    {
        return $this->places;
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function toWorkflow(): WorkflowInterface
    {
        $definition = (new DefinitionBuilder($this->places, $this->transitions))
            ->setInitialPlaces($this->initialPlace)
            ->build();

        // TODO: Pass dispatcher so it's possible to dispatch events and listen to them with Laravel event listeners.
        return match ($this->type) {
            Type::StateMachine => new StateMachine($definition, $this->markingStore, null, $this->name),
            Type::Workflow => new Workflow($definition, $this->markingStore, null, $this->name),
        };
    }
}
