<?php

namespace Tests\Feature\Branch;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Branch $branch;

    private Manager $manager;

    private Employee $employee;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager  = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->customer = Customer::factory()->create();
    }

    public function test_manager_can_access_empty_branches_index(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.branch.index'));

        $response->assertViewIs('manager.branch.index')->assertViewHas('branches', function ($branches) {
            return $branches->isEmpty();
        });
    }

    public function test_manager_can_access_branches_index_with_one_branch(): void
    {
        $this->actingAs($this->manager, 'manager');
        Branch::factory()->create();

        $response = $this->get(route('manager.branch.index'));

        $response->assertViewIs('manager.branch.index')->assertViewHas('branches', function ($branches) {
            return $branches->count() === 1;
        });
    }

    public function test_manager_can_access_branches_index_with_pagination(): void
    {
        $this->actingAs($this->manager, 'manager');
        Branch::factory(25)->create();

        $response = $this->get(route('manager.branch.index'));

        $response->assertViewIs('manager.branch.index')->assertViewHas('branches', function ($paginator) {
            return $paginator->hasPages();
        });
    }

    public function test_manager_can_store_branch_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $place_id = $this->faker->unique()->uuid();

        $response = $this->post(route('manager.branch.store'), [
            'place_id'    => $place_id,
            'name'        => 'Test Branch',
            'address'     => 'Test Address',
            'latitude'    => '35.41',
            'longitude'   => '35.41',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('manager.branch.index'));

        $this->assertDatabaseHas('branches', [
            'place_id'    => $place_id,
            'name'        => 'Test Branch',
            'address'     => 'Test Address',
            'latitude'    => '35.41',
            'longitude'   => '35.41',
            'description' => 'Test Description',
        ]);
    }

    public function test_manager_can_not_store_branch_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch = Branch::factory()->create();

        $response = $this->post(route('manager.branch.store'), [
            'place_id'    => $branch->place_id,
            'name'        => $branch->name,
            'address'     => $branch->address,
            'latitude'    => $branch->latitude,
            'longitude'   => $branch->longitude,
            'description' => $branch->description,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_manager_can_show_branch(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch = Branch::factory()->create();

        $response = $this->get(route('manager.branch.show', ['branch' => $branch->id]));

        $response->assertViewIs('manager.branch.show')->assertViewHas(['branch' => $branch]);
    }

    public function test_manager_can_update_branch(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch = Branch::factory()->create();

        $response = $this->put(route('manager.branch.update', ['branch' => $branch->id]), [
            'name' => 'Updated Test Branch',
        ]);

        $response->assertRedirect(route('manager.branch.show', ['branch' => $branch->id]));

        $this->assertDatabaseHas('branches', [
            'id'   => $branch->id,
            'name' => 'Updated Test Branch',
        ]);
    }

    public function test_manager_can_delete_branch(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch = Branch::factory()->create();

        $response = $this->delete(route('manager.branch.destroy', ['branch' => $branch->id]));

        $response->assertSessionHas('success', 'Sucursal borrada exitosamente');

        $this->assertDatabaseMissing('branches', [
            'id' => $branch->id,
        ]);
    }

    public function test_manager_can_edit_branch_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch = Branch::factory()->create();

        $response = $this->get(route('manager.branch.edit', ['branch' => $branch->id]));

        $response->assertViewIs('manager.branch.edit')->assertViewHas('branch', $branch);
    }

    public function test_manager_can_not_edit_branch_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.branch.edit', ['branch' => Branch::max('id') + 1]));

        $response->assertNotFound();
    }

    public function test_customer_can_not_access_branches_index(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('manager.branch.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_store_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->post(route('manager.branch.store'), [
            'name' => 'Guest Branch',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_update_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch = Branch::factory()->create();

        $response = $this->put(route('manager.branch.update', ['branch' => $branch->id]), [
            'name' => 'Updated Guest Branch',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_delete_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch = Branch::factory()->create();

        $response = $this->delete(route('manager.branch.destroy', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_edit_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch = Branch::factory()->create();

        $response = $this->get(route('manager.branch.edit', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_access_branches_index(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->get(route('manager.branch.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_store_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('manager.branch.store'), [
            'name'        => 'Test Branch',
            'address'     => 'Test Address',
            'latitude'    => '35.41',
            'longitude'   => '35.41',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_update_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch = Branch::factory()->create();

        $response = $this->put(route('manager.branch.update', ['branch' => $branch->id]), [
            'name' => 'Updated Guest Branch',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_delete_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch = Branch::factory()->create();

        $response = $this->delete(route('manager.branch.destroy', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_edit_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch = Branch::factory()->create();

        $response = $this->get(route('manager.branch.edit', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_access_branches_index(): void
    {
        $response = $this->get(route('manager.branch.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_store_branches(): void
    {
        $response = $this->post(route('manager.branch.store'), [
            'name'        => 'Test Branch',
            'address'     => 'Test Address',
            'latitude'    => '35.41',
            'longitude'   => '35.41',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_update_branch(): void
    {
        $branch = Branch::factory()->create();

        $response = $this->put(route('manager.branch.update', ['branch' => $branch->id]), [
            'name' => 'Updated Guest Branch',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_delete_branch(): void
    {
        $branch = Branch::factory()->create();

        $response = $this->delete(route('manager.branch.destroy', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_edit_branch(): void
    {
        $branch = Branch::factory()->create();

        $response = $this->get(route('manager.branch.edit', ['branch' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }
}
