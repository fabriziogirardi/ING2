<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JsonException;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Customer $customer;
    protected function setup(): void
    {
        parent::setup();

        $person = Person::factory()->create([
            'email' => "test@test.com"
        ]);
        $this->customer = Customer::factory()->create([
            'person_id' => $person->id,
            'password' => bcrypt('Password123!')
        ]);
    }

    public function test_customer_can_see_login_page(): void
    {
        $response = $this->get(route('customer.login'));
        $response->assertStatus(200);
    }

    /**
     * @throws JsonException
     */
    public function test_customer_can_login()
    {
        $response = $this->post(route('customer.login'), [
            'email' => $this->customer->person->email,
            'password' => 'Password123!',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->customer, 'customer');
    }

    public function test_customer_can_logout(){
        $this->actingAs($this->customer,'customer');

        $response = $this->get(route('customer.logout'));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertGuest('customer');
    }
}
