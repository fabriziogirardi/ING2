<?php

namespace Products;

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Manager::factory()->create();
    }

    public function test_add_new_brand_that_does_not_exist(): void
    {
        $this->actingAs($this->manager, 'manager');

        $response = $this->post(route('manager.product.brand.store'), [
            'name' => 'Test Brand',
        ]);

        $response->assertSessionHas('success', 'exito');

        $this->assertDatabaseHas('product_brands', [
            'name' => 'Test Brand',
        ]);
    }

    public function test_add_new_brand_that_exists()
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->post(route('manager.product.brand.store'), [
            'name' => $brand->name,
        ]);

        $response->assertStatus(302);
    }

    public function test_update_brand()
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->put(route('manager.product.brand.update', ['brand' => $brand->id]), [
            'name' => 'Updated Product Brand',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('product_brands', [
            'id'   => $brand->id,
            'name' => 'Updated Product Brand',
        ]);
    }

    public function test_delete_brand()
    {
        $this->actingAs($this->manager, 'manager');

        $brand = ProductBrand::factory()->create();

        $response = $this->delete(route('manager.product.brand.destroy', ['brand' => $brand->id]));

        $response->assertSessionHas('success', 'exito');

        $this->assertDatabaseMissing('product_brands', [
            'id' => $brand->id,
        ]);
    }
}
