<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Exceptions;

use UnexpectedValueException as SplUnexpectedValueException;

final class UnexpectedValueException extends SplUnexpectedValueException
{
    public static function unexpectedMissingValue(string $key): self
    {
        return new self(sprintf('Expected value "%s" to be set, but the value is null/undefined', $key));
    }
}
