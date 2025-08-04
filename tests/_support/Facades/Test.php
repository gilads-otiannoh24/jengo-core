<?php

namespace Tests\Support\Facades;

use Jengo\Core\Facades\Model;
use Tests\Support\Models\TestModel;

class Test extends Model
{
    protected static string $modelClass = TestModel::class;
}