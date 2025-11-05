<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Service;

use NickyMatthijssen\LaravelWorkflow\Data\RegisteredWorkflowInterface;

interface WorkflowLoaderInterface
{
    /**
     * @return list<RegisteredWorkflowInterface>
     */
    public function load(): array;
}
