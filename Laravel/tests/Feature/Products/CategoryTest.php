<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager  = Manager::factory()->create();
        $this->employee = Employee::factory()->create();
        $this->customer = Customer::factory()->create();
    }

    public function test_manager_can_access_index_with_empty_categories()
    {
        $this->actingAs($this->manager, 'manager');

        Category::destroy(Category::all());

        $response = $this->get(route('manager.category.index'));

        $response->assertViewIs('manager.category.index')->assertViewHas('categories', function ($categories) {
            return $categories->isEmpty();
        });
    }

    public function test_manager_can_access_index_with_one_category()
    {
        $this->actingAs($this->manager, 'manager');

        Category::destroy(Category::all());
        Category::factory()->create();

        $response = $this->get(route('manager.category.index'));

        $response->assertViewIs('manager.category.index')->assertViewHas('categories', function ($categories) {
            return $categories->count() === 1;
        });
    }

    public function test_manager_can_access_index_with_paginated_categories()
    {
        $this->actingAs($this->manager, 'manager');

        Category::destroy(Category::all());
        Category::factory(25)->create();

        $response = $this->get(route('manager.category.index'));

        $response->assertViewIs('manager.category.index')->assertViewHas('categories', function ($paginator) {
            return $paginator->hasPages();
        });
    }

    public function test_manager_can_create_category()
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.category.create'));

        $response->assertViewIs('manager.category.create');
    }

    public function test_manager_can_show_category()
    {
        $this->actingAs($this->manager, 'manager');

        $category = Category::factory()->create();

        $response = $this->get(route('manager.category.show', ['category' => $category->id]));

        $response->assertViewIs('manager.category.show')->assertViewHas([
            'category' => $category,
        ]);
    }

    public function test_manager_can_store_category_that_does_not_exists()
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.category.store'), [
            'name'        => 'Test Category Name',
            'description' => 'Test Category Description',
        ]);

        $response->assertRedirect(route('manager.category.index'));

        $this->assertDatabaseHas('categories', [
            'name'        => 'Test Category Name',
            'description' => 'Test Category Description',
        ]);
    }

    public function test_manager_can_not_store_category_that_exists()
    {
        $this->actingAs($this->manager, 'manager');

        $category = Category::factory()->create();

        $response = $this->post(route('manager.category.store'), [
            'name'        => $category->name,
            'description' => $category->description,
            'parent_id'   => $category->parent_id,
        ]);

        $response->assertStatus(302);
    }

    public function test_manager_can_edit_category_that_exists()
    {
        $this->actingAs($this->manager, 'manager');

        $category = Category::factory()->create();

        $response = $this->get(route('manager.category.edit', ['category' => $category->id]));

        $response->assertViewIs('manager.category.edit')->assertViewHas([
            'category' => $category,
        ]);
    }

    public function test_manager_cannot_edit_category_that_does_not_exist()
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->get(route('manager.category.edit', ['category' => Category::max('id') + 1]));

        $response->assertNotFound();
    }

    public function test_manager_can_update_category()
    {
        $this->actingAs($this->manager, 'manager');

        $category = Category::factory()->create();

        $response = $this->put(route('manager.category.update', ['category' => $category->id]), [
            'name'        => 'Updated Category Name',
            'description' => 'Updated Category Description',
        ]);

        $response->assertRedirect(route('manager.category.show', ['category' => $category->id]));
    }

    public function test_customer_can_not_access_categories_index()
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->get(route('manager.category.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_store_category()
    {
        $this->actingAs($this->customer, 'customer');

        $response = $this->post(route('manager.category.store'), [
            'name'        => 'Test Category Name',
            'description' => 'Test Category Description',
            'parent_id'   => null,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_edit_category()
    {
        $this->actingAs($this->customer, 'customer');

        $category = Category::factory()->create();

        $response = $this->get(route('manager.category.edit', ['category' => $category->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_customer_can_not_update_category()
    {
        $this->actingAs($this->customer, 'customer');

        $category = Category::factory()->create();

        $response = $this->put(route('manager.category.update', ['category' => $category->id]), [
            'name'        => 'Updated Category Name',
            'description' => 'Updated Category Description',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_access_categories_index()
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->get(route('manager.category.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_store_category()
    {
        $this->actingAs($this->employee, 'employee');

        $response = $this->post(route('manager.category.store'), [
            'name'        => 'Test Category Name',
            'description' => 'Test Category Description',
            'parent_id'   => null,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_edit_category()
    {
        $this->actingAs($this->employee, 'employee');

        $category = Category::factory()->create();

        $response = $this->get(route('manager.category.edit', ['category' => $category->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_employee_can_not_update_category()
    {
        $this->actingAs($this->employee, 'employee');

        $category = Category::factory()->create();

        $response = $this->put(route('manager.category.update', ['category' => $category->id]), [
            'name'        => 'Updated Category Name',
            'description' => 'Updated Category Description',
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_access_categories_index()
    {
        $response = $this->get(route('manager.category.index'));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_store_category()
    {
        $response = $this->post(route('manager.category.store'), [
            'name'        => 'Test Category Name',
            'description' => 'Test Category Description',
            'parent_id'   => null,
        ]);

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_edit_category()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('manager.category.edit', ['category' => $category->id]));

        $response->assertRedirect(route('manager.login'));
    }

    public function test_guest_can_not_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->put(route('manager.category.update', ['category' => $category->id]), [
            'name'        => 'Updated Category Name',
            'description' => 'Updated Category Description',
        ]);

        $response->assertRedirect(route('manager.login'));
    }
}
