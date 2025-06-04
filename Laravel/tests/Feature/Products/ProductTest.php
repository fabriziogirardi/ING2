<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
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


    public function test_manager_can_access_empty_products_index(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.product.index'));

        $response->assertViewIs('manager.product.index')->assertViewHas('products', function ($products) {
            return $products->isEmpty();
        });
    }

    public function test_manager_can_access_products_index_with_one_product(): void
    {
        $this->actingAs($this->manager, 'manager');

        Product::factory()->create();

        $response = $this->get(route('manager.product.index'));

        $response->assertViewIs('manager.product.index')->assertViewHas('products', function ($products) {
            return $products->count() === 1;
        });
    }

    public function test_manager_can_access_products_index_with_pagination(): void
    {
        $this->actingAs($this->manager, 'manager');

        Product::factory()->count(25)->create();

        $response = $this->get(route('manager.product.index'));

        $response->assertViewIs('manager.product.index')->assertViewHas('products', function ($paginator) {
            return $paginator->hasPages();
        });
    }

    public function test_manager_can_store_product_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $productModel = ProductModel::factory()->create();

        Storage::fake();
        $file = UploadedFile::fake()->image('product.jpg');

        $this->assertDatabaseEmpty('products');
        $this->assertDatabaseEmpty('product_images');

        $response = $this->post(route('manager.product.store'), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 12.50,
            'min_days' => 1,
            'product_model_id' => $productModel->id,
            'images' => [$file],
        ]);

        $response->assertRedirect(route('manager.product.create'));
        $response->assertSessionHas('success',__('manager/product.created'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 12.50,
            'min_days' => 1,
            'product_model_id' => $productModel->id,
        ]);

        $this->assertTrue(Product::withCount('images')->first()->images_count == 1);

        Storage::assertExists(ProductImage::first()->path);
    }

    public function test_manager_can_show_product(): void
    {
        $this->actingAs($this->manager, 'manager');

        $product = Product::factory()->create();

        $response = $this->get(route('manager.product.show', ['product' => $product->id]));

        $response->assertViewIs('manager.product.show')->assertViewHas([
            'product' => $product,
        ]);
    }

    public function test_manager_can_delete_product(): void
    {
        $this->actingAs($this->manager, 'manager');

        $product = Product::factory()->create();

        $response = $this->delete(route('manager.product.destroy', ['product' => $product->id]));

        $response->assertSessionHas('success', __('manager/product.deleted'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_manager_can_edit_product_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $product = Product::factory()->create();

        $response = $this->get(route('manager.product.edit', ['product' => $product->id]));

        $response->assertViewIs('manager.product.edit')->assertViewHas('product', $product);
    }

    public function test_manager_can_not_edit_product_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.product.edit', ['product' => Product::max('id') + 1]));

        $response->assertNotFound();
    }

    public function test_customer_can_not_access_products_index(): void
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('manager.product.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_store_product(): void
    {
        $this->actingAs($this->customer, 'customer');

        $productModel = ProductModel::factory()->create();

        $response = $this->post(route('manager.product.store'), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 12.50,
            'min_days' => 1,
            'product_model_id' => $productModel->id,
            ''
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_manager_can_update_product(): void
    {
        $this->actingAs($this->manager, 'manager');

        $product = Product::factory()->create();

        $response = $this->put(route('manager.product.update', ['product' => $product->id]), [
            'name' => 'Updated Test Product',
        ]);

        $response->assertRedirect(route('manager.product.show', ['product' => $product->id]));

        $this->assertDatabaseHas('products', [
            'id'   => $product->id,
            'name' => 'Updated Test Product',
        ]);
    }

    public function test_customer_can_not_update_product(): void
    {
        $this->actingAs($this->customer, 'customer');

        $product = Product::factory()->create();

        $response = $this->put(route('manager.product.update', ['product' => $product->id]), [
            'name' => 'Updated Test Product',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_delete_product(): void
    {
        $this->actingAs($this->customer, 'customer');

        $product = Product::factory()->create();

        $response = $this->delete(route('manager.product.destroy', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_edit_product(): void
    {
        $this->actingAs($this->customer, 'customer');

        $product = Product::factory()->create();

        $response = $this->get(route('manager.product.edit', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_access_products_index(): void
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->get(route('manager.product.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_store_product(): void
    {
        $this->actingAs($this->employee, 'employee');

        $productModel = ProductModel::factory()->create();

        $response = $this->post(route('manager.product.store'), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 12.50,
            'min_days' => 1,
            'product_model_id' => $productModel->id,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_update_product(): void
    {
        $this->actingAs($this->employee, 'employee');

        $product = Product::factory()->create();

        $response = $this->put(route('manager.product.update', ['product' => $product->id]), [
            'name' => 'Updated Test Product',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_delete_product(): void
    {
        $this->actingAs($this->employee, 'employee');

        $product = Product::factory()->create();

        $response = $this->delete(route('manager.product.destroy', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_edit_product(): void
    {
        $this->actingAs($this->employee, 'employee');

        $product = Product::factory()->create();

        $response = $this->get(route('manager.product.edit', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_access_products_index(): void
    {
        $response = $this->get(route('manager.product.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_store_product(): void
    {
        $productModel = ProductModel::factory()->create();

        $response = $this->post(route('manager.product.store'), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 12.50,
            'min_days' => 1,
            'product_model_id' => $productModel->id,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->put(route('manager.product.update', ['product' => $product->id]), [
            'name' => 'Updated Test Product',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('manager.product.destroy', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_edit_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('manager.product.edit', ['product' => $product->id]));

        $response->assertRedirect(route('manager.login'));
    }
}
