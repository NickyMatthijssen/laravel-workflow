<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\MarkingStores;

use Illuminate\Database\Eloquent\Model;
use NickyMatthijssen\LaravelWorkflow\Exceptions\InvalidSubjectException;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

final readonly class EloquentMarkingStore implements MarkingStoreInterface
{
    public function __construct(
        private bool $singleState = false,
        private string $property = 'marking',
    ) {}

    public function getMarking(object $subject): Marking
    {
        if (! $subject instanceof Model) {
            throw new InvalidSubjectException($subject);
        }

        $marking = $subject->{$this->property};
        if ($marking === null) {
            return new Marking;
        }

        if ($this->singleState) {
            $marking = [(string) $marking => 1];
        } elseif (! \is_array($marking)) {
            throw new \LogicException(\sprintf('The marking stored in "%s::$%s" is not an array and the Workflow\'s Marking store is instantiated with $singleState=false.', get_debug_type($subject), $this->property));
        }

        return new Marking($marking);
    }

    public function setMarking(object $subject, Marking $marking, array $context = []): void
    {
        if (! $subject instanceof Model) {
            throw new InvalidSubjectException($subject);
        }

        $marking = $marking->getPlaces();

        if ($this->singleState) {
            $marking = key($marking);
        }

        $subject->{$this->property} = $marking;
    }
}
