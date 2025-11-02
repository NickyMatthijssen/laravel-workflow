<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Factory;

use NickyMatthijssen\LaravelWorkflow\Data\RegisteredWorkflowInterface;
use NickyMatthijssen\LaravelWorkflow\Exceptions\UnexpectedValueException;

interface RegisteredWorkflowFactoryInterface
{
    /**
     * @throws UnexpectedValueException
     */
    public function fromWorkflowConfiguration(string $name, array $data): RegisteredWorkflowInterface;
}
