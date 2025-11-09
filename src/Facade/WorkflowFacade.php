<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Facade;

use Illuminate\Support\Facades\Facade;
use ShinobiZero\LaravelWorkflow\Service\WorkflowManagerInterface;

final class WorkflowFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WorkflowManagerInterface::class;
    }
}
