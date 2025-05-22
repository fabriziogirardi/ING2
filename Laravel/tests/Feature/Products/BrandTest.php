<?php

namespace Products;

use App\Models\ProductBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private ProductBrand $brand;

    protected function setUp(): void
    {
        parent::setUp();
        $this->brand = ProductBrand::factory()->create();
    }

    public function test_add_new_brand_that_does_not_exist(): void
    {
        $response = $this->post(route('manager.product.brand'), [
            'name' => 'Test Brand',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('product_brands', [
            'name' => 'Test Brand',
        ]);
    }

    public function test_add_new_brand_that_exists()
    {
        $response = $this->post(route('manager.product.brand'), [
            'name' => $this->brand->name,
        ]);

        $response->assertStatus(409);
        $response->assertJson(['name.unique' => 'La marca ya existe.']);
    }
}
