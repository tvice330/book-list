<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected static bool $migrated = false;

    protected function setUp(): void
    {
        if (! static::$migrated) {
            $this->refreshApplication();
            Artisan::call('migrate:fresh', ['--seed' => false]);
            static::$migrated = true;
        }

        parent::setUp();
    }
}
