<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Enums;

enum Type: string
{
    case Workflow = 'workflow';
    case StateMachine = 'state_machine';
}
