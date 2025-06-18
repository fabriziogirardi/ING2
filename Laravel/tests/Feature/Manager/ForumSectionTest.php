<?php

namespace Tests\Feature\Manager;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\ForumSection;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumSectionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    public function test_manager_can_access_index_with_no_sections()
    {
        $this->actingAs($this->manager, 'manager');

        ForumSection::destroy(ForumSection::all());

        $response = $this->get(route('manager.sections.index'));

        $response->assertViewIs('manager.sections.index')->assertViewMissing('sections');
    }

    public function test_manager_can_access_index_with_sections(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.sections.index'));

        $response->assertViewIs('manager.sections.index');
    }

    public function test_manager_can_store_section_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.sections.store'), [
            'name' => 'Test Section',
        ]);

        $response->assertRedirect(route('manager.sections.index'));

        $this->assertDatabaseHas('forum_sections', [
            'name' => 'Test Section',
        ]);
    }

    public function test_manager_can_store_section_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $section = ForumSection::factory()->create();

        $response = $this->post(route('manager.sections.store'), [
            'name' => $section->name,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_manager_can_update_section_with_a_name_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $section = ForumSection::factory()->create();

        $response = $this->put(route('manager.sections.update', ['section' => $section->id]), [
            'name' => 'Updated Section Name',
        ]);

        $response->assertRedirect(route('manager.sections.index'));

        $this->assertDatabaseHas('forum_sections', [
            'name' => 'Updated Section Name',
        ]);
    }

    public function test_manager_can_update_section_with_a_name_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $section = ForumSection::factory()->create();

        $response = $this->put(route('manager.sections.update', ['section' => $section->id]), [
            'name' => $section->name,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_manager_can_delete_sections(): void
    {
        $this->actingAs($this->manager, 'manager');

        $section = ForumSection::factory()->create();

        $response = $this->delete(route('manager.sections.destroy', ['section' => $section->id]));

        $response->assertRedirect(route('manager.sections.index'));
    }

    public function test_employee_can_not_store_sections(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('manager.sections.store'), [
            'name' => 'Test Section',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_update_sections(): void
    {
        $this->actingAs($this->employee, 'employee');

        $section = ForumSection::factory()->create();

        $response = $this->put(route('manager.sections.update', ['section' => $section->id]), [
            'name' => 'Updated Section Name',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_delete_sections(): void
    {
        $this->actingAs($this->employee, 'employee');

        $section = ForumSection::factory()->create();

        $response = $this->delete(route('manager.sections.destroy', ['section' => $section->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_store_sections(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->post(route('manager.sections.store'), [
            'name' => 'Test Section',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_update_sections(): void
    {
        $this->actingAs($this->customer, 'customer');

        $section = ForumSection::factory()->create();

        $response = $this->put(route('manager.sections.update', ['section' => $section->id]), [
            'name' => 'Updated Section Name',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_delete_sections(): void
    {
        $this->actingAs($this->customer, 'customer');

        $section = ForumSection::factory()->create();

        $response = $this->delete(route('manager.sections.destroy', ['section' => $section->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_store_sections(): void
    {
        $section = ForumSection::factory()->make();

        $response = $this->post(route('manager.sections.store'), [
            'name' => $section->name,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_update_sections(): void
    {
        $section = ForumSection::factory()->create();

        $response = $this->put(route('manager.sections.update', ['section' => $section->id]), [
            'name' => 'Updated Section Name',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_delete_sections(): void
    {
        $section = ForumSection::factory()->create();

        $response = $this->delete(route('manager.sections.destroy', ['section' => $section->id]));

        $response->assertRedirect(route('manager.login'));
    }
}
