<?php

namespace Tests;

use CodeIgniter\Test\DatabaseTestTrait;

class DatabaseTestCase extends TestCase
{
    use DatabaseTestTrait;

    protected array $tables = [];
    // For Migrations
    protected $migrate = true;
    protected $migrateOnce = false;
    protected $refresh = true;
    protected $namespace = 'Tests\Support';

    // For Seeds
    protected $seedOnce = false;
    protected $seed = 'TestSeeder';
    protected $basePath = 'tests/_support/Database';

    protected function setup(
    ): void {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}