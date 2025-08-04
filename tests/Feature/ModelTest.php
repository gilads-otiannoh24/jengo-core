<?php

use Tests\DatabaseTestCase;
use Tests\Support\Facades\Test;

uses(DatabaseTestCase::class);

test("Model can be called statically", function () {
    $users = Test::findAll();

    expect(count($users))->toBe(100);
});