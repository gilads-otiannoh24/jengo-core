<?php

namespace Tests\Support\Models;

use CodeIgniter\Model;

class TestModel extends Model
{
    public $table = "test_table";

    protected $allowedFields = [
        'name',
        'email',
    ];

    public function fake(\Faker\Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'email' => $faker->email(),
        ];
    }
}