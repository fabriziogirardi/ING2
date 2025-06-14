<?php

namespace Tests\Feature\Products;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductDetailsTest extends TestCase
{
    use RefreshDatabase , WithFaker;

    private Manager $manager;

    private Employee $employee;

    private Customer $customer;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager  = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->product  = Product::factory()->create();
    }

    public function test_manager_can_access_product_details()
    {
        $this->actingAs($this->manager, 'manager');
        $response = $this->get(route('catalog.show', ['product' => $this->product]));
        $response->assertViewIs('catalog.show');
        $response->assertViewHas('product', $this->product);
    }

    public function test_employee_can_access_product_details()
    {
        $this->actingAs($this->employee, 'employee');
        $response = $this->get(route('catalog.show', ['product' => $this->product->id]));
        $response->assertViewIs('catalog.show');
        $response->assertViewHas('product', $this->product);
    }

    public function test_customer_can_access_product_details()
    {
        $this->actingAs($this->customer, 'customer');
        $response = $this->get(route('catalog.show', ['product' => $this->product]));
        $response->assertViewIs('catalog.show');
        $response->assertViewHas('product', $this->product);
    }

    public function test_guest_cannot_access_product_details()
    {
        $response = $this->get(route('catalog.show', ['product' => $this->product]));
        $response->assertRedirect(route('customer.login'));
    }
}
