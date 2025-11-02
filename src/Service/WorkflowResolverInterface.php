<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Service;

interface WorkflowResolverInterface
{
    public function resolve(): array;
}
