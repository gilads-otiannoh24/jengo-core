<?php

namespace Tests;

use CodeIgniter\Test\ControllerTestTrait;


class ControllerTestCase extends TestCase
{
    use ControllerTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }
    protected function tearDown(): void
    {
        parent::tearDown();

        session()->removeTempdata("errors");
    }
}