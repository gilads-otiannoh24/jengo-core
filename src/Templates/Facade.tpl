<?php

namespace {{ namespace }};

use Jengo\Facades\ModelProxy;
use {{ modelNamespace }};

class {{ class }} extends ModelProxy
{
    protected static string $modelClass = {{ modelClass }}::class;
}
