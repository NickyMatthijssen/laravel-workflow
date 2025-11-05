<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Facade;

use Illuminate\Support\Facades\Facade;
use NickyMatthijssen\LaravelWorkflow\Service\WorkflowManagerInterface;

final class WorkflowFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WorkflowManagerInterface::class;
    }
}
