<?php

namespace Tests\Feature\Products;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $model;

    private $brand;

    private $manager;

    private $employee;

    private $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager  = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->brand    = ProductBrand::factory()->create();
        $this->model    = ProductModel::factory()->create([
            'product_brand_id' => $this->brand->id,
        ]);
    }

    public function test_manager_can_store_stock_of_product_with_a_branch_that_exist_with_a_valid_quantity(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 10,
        ]);

        $response->assertRedirect(route('manager.products.index'));

        $this->assertDatabaseHas('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => 10,
        ]);
    }

    public function test_manager_cannot_store_stock_of_product_of_a_branch_with_a_negative_quantity(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => -1,
        ]);

        $response->assertSessionHasErrors(['quantity']);

        $this->assertDatabaseMissing('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => -1,
        ]);
    }

    public function test_manager_cannot_store_stock_of_product_of_a_branch_with_zero_quantity(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 0,
        ]);

        $response->assertSessionHasErrors(['quantity']);

        $this->assertDatabaseMissing('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => 0,
        ]);
    }

    public function test_manager_can_update_stock_of_product_with_a_branch_with_a_valid_quantity()
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        // Create the branch_product relationship first
        BranchProduct::create([
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => 10,
        ]);

        $response = $this->put(route('manager.products.stock.update', [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
        ]), [
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('manager.products.show', ['product_id' => $product->id]));

        $this->assertDatabaseHas('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);
    }

    public function test_manager_can_update_stock_of_product_with_a_branch_with_a_negative_quantity()
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->put(route('manager.products.stock.update', ['product_id' => $product->id, 'branch_id' => $branch->id]), [
            'quantity' => -1,
        ]);

        $response->assertSessionHasErrors(['quantity']);

        $this->assertDatabaseMissing('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => -1,
        ]);
    }

    public function test_manager_can_update_stock_of_product_with_a_branch_with_zero_quantity()
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->put(route('manager.products.stock.update', ['product_id' => $product->id, 'branch_id' => $branch->id]), [
            'quantity' => 0,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
            'quantity'   => 0,
        ]);
    }

    public function test_manager_can_destroy_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->manager, 'manager');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        // First, store some stock
        $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 10,
        ]);

        $response = $this->delete(route('manager.products.stock.destroy', ['product_id' => $product->id, 'branch_id' => $branch->id]));

        $response->assertRedirect(route('manager.products.index'));

        $this->assertSoftDeleted('branch_product', [
            'branch_id'  => $branch->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_employee_cannot_store_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 10,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_cannot_update_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->put(route('manager.products.stock.update', ['product_id' => $product->id, 'branch_id' => $branch->id]), [
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_cannot_destroy_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->employee, 'employee');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->delete(route('manager.products.stock.destroy', ['product_id' => $product->id, 'branch_id' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_cannot_store_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 10,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_cannot_update_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->put(route('manager.products.stock.update', ['product_id' => $product->id, 'branch_id' => $branch->id]), [
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_cannot_destroy_stock_of_product_with_a_branch(): void
    {
        $this->actingAs($this->customer, 'customer');

        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->delete(route('manager.products.stock.destroy', ['product_id' => $product->id, 'branch_id' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_cannot_store_stock_of_product_with_a_branch(): void
    {
        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->post(route('manager.products.stock.store'), [
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'quantity'   => 10,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_cannot_update_stock_of_product_with_a_branch(): void
    {
        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->put(route('manager.products.stock.update', ['product_id' => $product->id, 'branch_id' => $branch->id]), [
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_cannot_destroy_stock_of_product_with_a_branch(): void
    {
        $branch  = Branch::factory()->create();
        $product = Product::factory()->create([
            'product_model_id' => $this->model->id,
        ]);

        $response = $this->delete(route('manager.products.stock.destroy', ['product_id' => $product->id, 'branch_id' => $branch->id]));

        $response->assertRedirect(route('manager.login'));
    }
}
