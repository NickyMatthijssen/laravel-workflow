<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Service;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflowInterface;

interface WorkflowLoaderInterface
{
    /**
     * @return list<RegisteredWorkflowInterface>
     */
    public function load(): array;
}
