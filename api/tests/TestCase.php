<?php

namespace Tests;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createSupplier(array $args = []): Supplier
    {
        return Supplier::factory()->create($args);
    }

    public function createSuppliers(int $qty, array $args = []): Collection
    {
        return Supplier::factory()->createMany($qty, $args);
    }

    public function makeSupplier(array $args = []): Supplier
    {
        return Supplier::factory()->makeOne($args);
    }

    public function makeSuppliers(int $qty, array $args = []): Supplier
    {
        return Supplier::factory($qty)->make($args);
    }
}
