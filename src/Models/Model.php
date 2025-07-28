<?php

namespace Jengo\Models;

use CodeIgniter\Model as CI4Model;
use Illuminate\Support\Collection;

class Model extends CI4Model
{
    public static function __callStatic($method, $args): Collection|object|array
    {
        $instance = new static();

        $result = call_user_func_array([$instance, $method], $args);

        // Optionally wrap result in a Collection if it's arrayable
        if (is_array($result)) {
            return collect($result);
        }

        return $result;
    }

    /**
     * @param array<bool|float|int|object|string|null>|object|null $row
     * @param bool $returnID
     */

    public function insert($row = null, $returnID = true)
    {
        $id = parent::insert($row, $returnID);

        if (parent::errors()) {
            session()->setFlashdata("errors", parent::errors());
        }

        return $id;
    }

    /**
     * @param array|int|string|null $id
     * @param array<bool|float|int|object|string|null>|object|null $row
     */
    public function update($id = null, $row = null): bool
    {
        $return = parent::update($id, $row);

        if (parent::errors()) {
            session()->setFlashdata("errors", parent::errors());
        }

        return $return;
    }
}