<?php

namespace Tests\Feature\Manager;

use App\Models\Employee;
use App\Models\GovernmentIdType;
use App\Models\Manager;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManageEmployeeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Manager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
    }

    public function test_manager_can_see_employee_list_page(): void
    {
        $this->actingAs($this->manager, 'manager');
        $response = $this->get(route('manager.employee.index'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_see_employee_list_page(): void
    {
        $response = $this->get(route('manager.employee.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_manager_can_see_add_employee_page(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.employee.create'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_see_add_employee_page(): void
    {
        $response = $this->get(route('manager.employee.create'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_manager_can_add_employee(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.employee.store'), [
            'first_name'            => 'Juan',
            'last_name'             => 'Lopez',
            'email'                 => 'juanlopez@gmail.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'government_id_type_id' => GovernmentIdType::firstOrCreate(['name' => 'DNI'])->id,
            'government_id_number'  => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('manager.employee.index'));
        $response->assertSessionHas('success', __('manager.employee.created'));
        $this->assertDatabaseHas('people', [
            'first_name'            => 'Juan',
            'last_name'             => 'Lopez',
            'email'                 => 'juanlopez@gmail.com',
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '12345678',
        ]);
        $this->assertDatabaseHas('employees', [
            'person_id' => Person::where('email', 'juanlopez@gmail.com')->first()->id,
        ]);

        $employee = Employee::whereRelation('person', 'email', 'juanlopez@gmail.com')->first();

        $this->assertTrue(Hash::check('password', $employee->password));
    }
}
