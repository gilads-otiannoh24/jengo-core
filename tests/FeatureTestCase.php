<?php

namespace Tests;

use CodeIgniter\Test\FeatureTestTrait;

class FeatureTestCase extends TestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}