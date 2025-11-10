<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests\Mock;

use Illuminate\Database\Eloquent\Model;
use ShinobiZero\LaravelWorkflow\Concerns\InteractsWithWorkflow;

class TestModel extends Model
{
    use InteractsWithWorkflow;

    protected $fillable = ['status'];
}
