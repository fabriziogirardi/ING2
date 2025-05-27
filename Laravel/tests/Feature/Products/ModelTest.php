<?php

namespace Products;

use App\Models\Manager;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Manager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
    }

    public function test_manager_can_create_model_that_not_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.product.model.store'), [
            'name' => 'Test Model',
            'product_brand_id' => ProductBrand::factory()->create()->id,
        ]);

        $response->assertSessionHas('success', __('manager.model.created'));

        $this->assertDatabaseHas('product_models', [
            'name' => 'Test Model',
        ]);
    }

    public function test_manager_can_create_model_that_exists(): void
    {
        $this->actingAs($this->manager, 'manager');

        $model = ProductModel::factory()->create();

        $countBefore = ProductModel::count();

        $response = $this->post(route('manager.product.model.store'), [
            'name' => $model->name,
            'product_brand_id' => $model->product_brand_id,
        ]);

        $response->assertSessionHasErrors(['name']);

        $countAfter = ProductModel::count();

        $this->assertEquals($countBefore, $countAfter);
    }

    public function test_manager_can_update_model(): void
    {
        $this->actingAs($this->manager, 'manager');

        $model = ProductModel::factory()->create();

        $response = $this->put(route('manager.product.model.update', $model), [
            'name' => 'Updated Model',
            'product_brand_id' => $model->product_brand_id,
        ]);

        $response->assertSessionHas('success', __('manager.model.updated'));

        $this->assertDatabaseHas('product_models', [
            'id' => $model->id,
            'name' => 'Updated Model',
        ]);
    }

    public function test_manager_can_delete_model(): void
    {
        $this->actingAs($this->manager, 'manager');

        $model = ProductModel::factory()->create();

        $response = $this->delete(route('manager.product.model.destroy', $model));

        $response->assertRedirect(route('manager.product.model.index'));

        $response->assertSessionHas('success', __('manager.model.deleted'));

        $this->assertDatabaseMissing('product_models', [
            'id' => $model->id,
        ]);
    }

    public function test_manager_can_create_model_with_same_name_and_diferents_brands(): void
    {
        $this->actingAs($this->manager, 'manager');

        $brandA = ProductBrand::factory()->create();
        $brandB = ProductBrand::factory()->create();

        $modelName = 'Shared Model Name';

        // Create first model
        $responseA = $this->post(route('manager.product.model.store'), [
            'name' => $modelName,
            'product_brand_id' => $brandA->id,
        ]);
        $responseA->assertSessionHas('success', __('manager.model.created'));

        // Create second model with same name but different brand
        $responseB = $this->post(route('manager.product.model.store'), [
            'name' => $modelName,
            'product_brand_id' => $brandB->id,
        ]);
        $responseB->assertSessionHas('success', __('manager.model.created'));

        $this->assertDatabaseHas('product_models', [
            'name' => $modelName,
            'product_brand_id' => $brandA->id,
        ]);
        $this->assertDatabaseHas('product_models', [
            'name' => $modelName,
            'product_brand_id' => $brandB->id,
        ]);
    }

    public function test_guest_cannot_create_model(): void
    {
        $brand = ProductBrand::factory()->create();

        $response = $this->post(route('manager.product.model.store'), [
            'name' => 'Test Model',
            'product_brand_id' => $brand->id,
        ]);

        $response->assertRedirect(route('manager.login'));
        $response->assertStatus(302);
    }

    public function test_guest_cannot_update_model(): void
    {
        $model = ProductModel::factory()->create();

        $response = $this->put(route('manager.product.model.update', $model), [
            'name' => 'Updated Model',
            'product_brand_id' => $model->product_brand_id,
        ]);

        $response->assertRedirect(route('manager.login'));
        $response->assertStatus(302);
    }

    public function test_guest_cannot_delete_model(): void
    {
        $model = ProductModel::factory()->create();

        $response = $this->delete(route('manager.product.model.destroy', $model));

        $response->assertRedirect(route('manager.login'));
        $response->assertStatus(302);
    }
}
