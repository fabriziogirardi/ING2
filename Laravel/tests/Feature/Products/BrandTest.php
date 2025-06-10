<?php

namespace Tests\Feature\Products;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\ProductBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private ProductBrand $brand;

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

    public function test_manager_can_access_empty_brands_index(): void
    {
        $this->actingAs($this->manager, 'manager');

        ProductBrand::destroy(ProductBrand::all());

        $response = $this->get(route('manager.brand.index'));

        $response->assertViewIs('manager.brand.index')->assertDontSee('brands');
    }

    public function test_manager_can_access_brands_index_with_one_brand(): void
    {
        $this->actingAs($this->manager, 'manager');

        ProductBrand::destroy(ProductBrand::all());
        ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.index'));

        $response->assertViewIs('manager.brand.index')->assertViewHas('brands', function ($brands) {
            return $brands->count() === 1;
        });
    }

    public function test_manager_can_access_brands_index_with_pagination(): void
    {
        $this->actingAs($this->manager, 'manager');

        ProductBrand::factory()->count(25)->create();

        $response = $this->get(route('manager.brand.index'));

        $response->assertViewIs('manager.brand.index')->assertViewHas('brands', function ($paginator) {
            return $paginator->hasPages(); // Assuming default pagination is 10
        });
    }

    public function test_manager_can_store_brand_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.brand.store'), [
            'name' => 'Test Brand',
        ]);

        $response->assertRedirect(route('manager.brand.index'));

        $this->assertDatabaseHas('product_brands', [
            'name' => 'Test Brand',
        ]);
    }

    public function test_manager_can_not_store_brand_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->post(route('manager.brand.store'), [
            'name' => $brand->name,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_manager_can_show_brand(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.show', ['brand' => $brand->id]));

        $response->assertViewIs('manager.brand.show')->assertViewHas([
            'brand' => $brand,
        ]);
    }

    public function test_manager_can_update_brand(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->put(route('manager.brand.update', ['brand' => $brand->id]), [
            'name' => 'Updated Product Brand',
        ]);

        $response->assertRedirect(route('manager.brand.show', ['brand' => $brand->id]));

        $this->assertDatabaseHas('product_brands', [
            'id'   => $brand->id,
            'name' => 'Updated Product Brand',
        ]);
    }

    public function test_manager_can_delete_brand(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->delete(route('manager.brand.destroy', ['brand' => $brand->id]));

        $response->assertSessionHas('success', 'exito');
    }

    public function test_manager_can_edit_brand_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.edit', ['brand' => $brand->id]));

        $response->assertViewIs('manager.brand.edit')->assertViewHas('brand', $brand);
    }

    public function test_manager_can_not_edit_brand_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.brand.edit', ['brand' => ProductBrand::max('id') + 1]));

        $response->assertNotFound();
    }

    public function test_customer_can_not_access_brands_index(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('manager.brand.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_store_brand(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->post(route('manager.brand.store'), [
            'name' => 'Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_update_brand(): void
    {
        $this->actingAs($this->customer, 'customer');

        $brand = ProductBrand::factory()->create();

        $response = $this->put(route('manager.brand.update', ['brand' => $brand->id]), [
            'name' => 'Updated Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_delete_brand(): void
    {
        $this->actingAs($this->customer, 'customer');

        $brand = ProductBrand::factory()->create();

        $response = $this->delete(route('manager.brand.destroy', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_edit_brand(): void
    {
        $this->actingAs($this->customer, 'customer');

        $brand = ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.edit', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_access_brands_index(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->get(route('manager.brand.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_store_brand(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('manager.brand.store'), [
            'name' => 'Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_update_brand(): void
    {
        $this->actingAs($this->employee, 'employee');

        $brand = ProductBrand::factory()->create();

        $response = $this->put(route('manager.brand.update', ['brand' => $brand->id]), [
            'name' => 'Updated Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_delete_brand(): void
    {
        $this->actingAs($this->employee, 'employee');

        $brand = ProductBrand::factory()->create();

        $response = $this->delete(route('manager.brand.destroy', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_edit_brand(): void
    {
        $this->actingAs($this->employee, 'employee');

        $brand = ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.edit', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_access_brands_index(): void
    {
        $response = $this->get(route('manager.brand.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_store_brand(): void
    {
        $response = $this->post(route('manager.brand.store'), [
            'name' => 'Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_update_brand(): void
    {
        $brand = ProductBrand::factory()->create();

        $response = $this->put(route('manager.brand.update', ['brand' => $brand->id]), [
            'name' => 'Updated Guest Brand',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_delete_brand(): void
    {
        $brand = ProductBrand::factory()->create();

        $response = $this->delete(route('manager.brand.destroy', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_edit_brand(): void
    {
        $brand = ProductBrand::factory()->create();

        $response = $this->get(route('manager.brand.edit', ['brand' => $brand->id]));

        $response->assertRedirect(route('manager.login'));
    }
}
