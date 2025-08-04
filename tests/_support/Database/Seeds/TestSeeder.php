<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use Tests\Support\Models\TestModel;


class TestSeeder extends Seeder
{
    public function run(): void
    {
        $data = (new Fabricator(TestModel::class))->make(100);

        $this->db->table("test_table")->insertBatch($data);
    }
}