<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests\Mock;

use Illuminate\Database\Eloquent\Model;
use NickyMatthijssen\LaravelWorkflow\Concerns\InteractsWithWorkflow;

class TestModel extends Model
{
    use InteractsWithWorkflow;

    protected $fillable = ['status'];
}
