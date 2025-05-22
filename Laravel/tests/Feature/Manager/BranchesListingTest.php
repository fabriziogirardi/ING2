<?php

namespace Manager;

use App\Models\Branch;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchesListingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_listing_with_branches_no_authenticated(): void
    {
        Branch::factory()->count(10)->create();

        $response = $this->get(route('manager.branches.index'));

        $response->assertStatus(302);
    }

    public function test_listing_without_branches_no_authenticated(): void
    {
        $response = $this->get(route('manager.branches.index'));

        $response->assertStatus(302);
    }

    public function test_listing_with_branches_authenticated(): void
    {
        $this->actingAs($this->manager, 'manager');

        Branch::factory()->count(10)->create();

        $response = $this->get(route('manager.branches.index'));

        $response->assertStatus(200);
        $response->assertViewHas('branches');
    }

    public function test_listing_without_branches_authenticated(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.branches.index'));

        $response->assertStatus(200);
        $response->assertViewHas('branches', function ($branches) {
            return $branches->isEmpty();
        });
    }
}
