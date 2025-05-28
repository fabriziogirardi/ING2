<?php

namespace Tests\Feature\Employee;

use App\Models\Employee;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JsonException;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private Employee $employee;
    protected function setup(): void
    {
        parent::setup();

        $person = Person::factory()->create([
            'email' => "test@test.com"
        ]);
        $this->employee = employee::factory()->create([
            'person_id' => $person->id,
            'password' => bcrypt('Password123!')
        ]);
    }

    public function test_customer_can_see_login_page(): void
    {
        $response = $this->get(route('employee.login'));
        $response->assertStatus(200);
    }

    /**
     * @throws JsonException
     */
    public function test_customer_can_login()
    {
        $response = $this->post(route('employee.login'), [
            'email' => $this->employee->person->email,
            'password' => 'Password123!',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->employee, 'employee');
    }

    public function test_customer_can_logout(){
        $this->actingAs($this->employee,'employee');

        $response = $this->get(route('employee.logout'));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertGuest('employee');
    }
}
