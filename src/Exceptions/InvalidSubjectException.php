<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Exceptions;

use InvalidArgumentException;

final class InvalidSubjectException extends InvalidArgumentException
{
    public function __construct(object $subject)
    {
        parent::__construct(sprintf('The subject must be an Eloquent model, "%s" given', get_debug_type($subject)));
    }
}
