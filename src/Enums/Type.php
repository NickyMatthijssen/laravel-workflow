<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Enums;

enum Type: string
{
    case Workflow = 'workflow';
    case StateMachine = 'state_machine';
}
