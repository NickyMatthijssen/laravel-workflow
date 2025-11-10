<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Factory;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflowInterface;
use ShinobiZero\LaravelWorkflow\Exceptions\UnexpectedValueException;

interface RegisteredWorkflowFactoryInterface
{
    /**
     * @throws UnexpectedValueException
     */
    public function fromWorkflowConfiguration(string $name, array $data): RegisteredWorkflowInterface;
}
