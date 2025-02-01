<?php

namespace Jengo\Repositories;

class UserRepository
{
    public $model;

    public function __construct()
    {
        $config = config('Jengo');

        // @intelphense ignore-next-line
        $this->model = new $config->user_model;
    }

    public function checkForUser(string|int $id): array
    {
        // TODO: Implement checkForUser() method.
        $user = $this->model->find($id);

        return $user;
    }
}
