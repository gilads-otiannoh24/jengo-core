<?php

namespace Tests\Support\Facades;

use Jengo\Core\Facades\ModelFacade;
use Tests\Support\Models\TestModel;

class Test extends ModelFacade
{
    protected static string $modelClass = TestModel::class;
}