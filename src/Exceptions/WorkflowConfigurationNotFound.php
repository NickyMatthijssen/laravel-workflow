<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Exceptions;

use RuntimeException;

final class WorkflowConfigurationNotFound extends RuntimeException
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('Workflow configuration not found at path "%s"', $path));
    }
}
