<?php

namespace Tests\Feature\Employee;

use App\Mail\NewCustomerCreated;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterCustomerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $types = GovernmentIdType::factory()->createMany([
            ['name' => 'DNI'],
            ['name' => 'PAS'],
            ['name' => 'LE'],
            ['name' => 'LC'],
        ]);
        $this->idType        = $types[0];
        $this->idTypeDistint = $types[1];

        $this->employee = Employee::factory()->create();
    }

    public function test_customer_can_register_with_valid_data(): void
    {
        $this->actingAs($this->employee, 'employee');

        Mail::fake();

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertRedirect('/')->assertSessionHas('success');

        $response->assertStatus(302);

        Mail::assertSent(NewCustomerCreated::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_customer_can_register_with_valid_data_and_registered_person(): void
    {
        $this->actingAs($this->employee, 'employee');

        Mail::fake();

        Person::factory()->create([
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
        ]);

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertRedirect('/')->assertSessionHas('success');

        $response->assertStatus(302);

        Mail::assertSent(NewCustomerCreated::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_customer_can_register_with_valid_data_and_type_document_distint(): void
    {
        $this->actingAs($this->employee, 'employee');

        Mail::fake();

        $person = Person::factory()->create([
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'test@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idTypeDistint->id,
        ]);

        Customer::factory()->create([
            'person_id' => $person->id,
            'password'  => bcrypt('password'),
        ]);

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertRedirect('/')->assertSessionHas('success');

        $response->assertStatus(302);

        Mail::assertSent(NewCustomerCreated::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_registration_fails_with_missing_fields(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('employee.register_customer'), [
            // Missing all fields
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'government_id_number', 'birth_date']);
    }

    public function test_registration_fails_with_invalid_email(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'Jane',
            'last_name'             => 'Smith',
            'email'                 => 'not-an-email',
            'government_id_number'  => '87654321',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_fails_if_email_already_exists(): void
    {
        $this->actingAs($this->employee, 'employee');

        $person = Person::factory()->create([
            'first_name'            => 'Test',
            'last_name'             => 'Test',
            'email'                 => 'existing@example.com',
            'government_id_number'  => '11111111',
            'government_id_type_id' => $this->idType->id,
        ]);

        Customer::factory()->create([
            'person_id' => $person->id,
            'password'  => bcrypt('password'),
        ]);

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'Jane',
            'last_name'             => 'Smith',
            'email'                 => 'existing@example.com',
            'government_id_number'  => '87654321',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_fails_if_government_id_number_already_exists(): void
    {
        $this->actingAs($this->employee, 'employee');

        $person = Person::factory()->create([
            'first_name'            => 'Test',
            'last_name'             => 'Test',
            'email'                 => 'john@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
        ]);

        Customer::factory()->create([
            'person_id' => $person->id,
            'password'  => bcrypt('password'),
        ]);

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'Jane',
            'last_name'             => 'Smith',
            'email'                 => 'jane@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2000-01-01',
        ]);

        $response->assertSessionHasErrors(['government_id_number']);

    }

    public function test_registration_fails_if_is_minor_age(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('employee.register_customer'), [
            'first_name'            => 'Jane',
            'last_name'             => 'Smith',
            'email'                 => 'jane@example.com',
            'government_id_number'  => '12345678',
            'government_id_type_id' => $this->idType->id,
            'birth_date'            => '2010-01-01',
        ]);

        $response->assertSessionHasErrors(['birth_date']);
    }

    public function test_other_users_cannot_register()
    {
        $response = $this->get(route('employee.register_customer'));
        $response->assertStatus(302);
    }
}
