<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $person   = Person::factory()->create();
        $customer = Customer::factory()->create([
            'person_id' => $person->id,
            'password'  => bcrypt('oldpassword'),
        ]);

        $this->actingAs($customer, 'customer');
    }

    public function test_customer_can_change_password_with_valid_data()
    {
        $response = $this->post('/customer/reset-password', [
            'new_password'              => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/')->assertSessionHas('success');
    }

    public function test_customer_cannot_change_password_without_confirmation()
    {
        $response = $this->post('/customer/reset-password', [
            'new_password'              => 'newpassword123',
            'new_password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('new_password');
    }
}
