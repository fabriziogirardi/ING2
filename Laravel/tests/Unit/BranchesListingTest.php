<?php

namespace Tests\Unit;

use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchesListingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_listing_with_branches(): void
    {
        Branch::factory()->count(10)->create();

        $response = $this->get(route('branches.index'));

        $response->assertStatus(200);
        $response->assertViewHas('branches');
    }

    public function test_listing_without_branches(): void
    {
        $response = $this->get(route('branches.index'));

        $response->assertStatus(200);
        $response->assertViewHas('branches', function ($branches) {
            return $branches->isEmpty();
        });
        $response->assertSee('No hay sucursales para mostrar');
    }
}
