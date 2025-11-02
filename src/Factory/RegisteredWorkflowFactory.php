<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Factory;

use BackedEnum;
use Illuminate\Container\Attributes\Config;
use NickyMatthijssen\LaravelWorkflow\Data\RegisteredWorkflow;
use NickyMatthijssen\LaravelWorkflow\Data\RegisteredWorkflowInterface;
use NickyMatthijssen\LaravelWorkflow\Enums\Type;
use NickyMatthijssen\LaravelWorkflow\Exceptions\UnexpectedValueException;
use NickyMatthijssen\LaravelWorkflow\MarkingStores\EloquentMarkingStore;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;

final readonly class RegisteredWorkflowFactory implements RegisteredWorkflowFactoryInterface
{
    public function __construct(
        #[Config('workflow.default_marking_store')]
        private string $defaultMarkingStoreClass,
        #[Config('workflow.default_property')]
        private string $defaultProperty,
    ) {}

    public function fromWorkflowConfiguration(string $name, array $data): RegisteredWorkflowInterface
    {
        $type = $data['type'] ?? throw UnexpectedValueException::unexpectedMissingValue('type');
        if (! $type instanceof Type) {
            $type = Type::from($type);
        }

        $places = $data['places'] ?? null;
        $transitions = $data['transitions'] ?? null;
        $markingStoreClass = $data['marking_store'] ?? $this->defaultMarkingStoreClass;

        return new RegisteredWorkflow(
            $name,
            $type,
            $data['initial_place'] ?? throw UnexpectedValueException::unexpectedMissingValue('initial_place'),
            $data['supports'] ?? throw UnexpectedValueException::unexpectedMissingValue('supports'),
            null !== $places ? $this->getPlaces($places) : [],
            null !== $transitions ? $this->getTransitions($transitions) : [],
            $this->createMarkingStore($markingStoreClass, $type, $data['property'] ?? $this->defaultProperty),
        );
    }

    private function getPlaces(array $places): array
    {
        return array_map(static function (string|BackedEnum $place): string {
            if ($place instanceof BackedEnum) {
                return (string) $place->value;
            }

            return $place;
        }, $places);
    }

    private function getTransitions(array $transitions): array
    {
        return array_map(
            static fn (array $transition, string $name): Transition => new Transition($name, $transition['from'], $transition['to']),
            array_values($transitions),
            array_keys($transitions),
        );
    }

    /**
     * @param  class-string<MarkingStoreInterface>  $markingStoreClass
     */
    private function createMarkingStore(string $markingStoreClass, Type $type, ?string $property = null): MarkingStoreInterface
    {
        $isSingleState = Type::StateMachine === $type;

        return match ($markingStoreClass) {
            MethodMarkingStore::class => new MethodMarkingStore($isSingleState, $property),
            EloquentMarkingStore::class => new EloquentMarkingStore($isSingleState, $property),
        };
    }
}
