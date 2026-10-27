<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\Attributes\Seeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

#[Seeder(RoleSeeder::class)]
abstract class TestCase extends BaseTestCase
{
    //
}
