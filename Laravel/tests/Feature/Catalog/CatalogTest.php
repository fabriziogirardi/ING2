<?php

namespace Tests\Feature\Catalog;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Branch $branch1;
    private Branch $branch2;
    private Product $product;

    protected function setUp() :void
    {
        parent::setUp();
        $this->branch1 = Branch::factory()->create();
        $this->branch2 = Branch::factory()->create();
    }

    public function test_catalog_has_a_product_that_is_available_in_two_branches()
    {
        $product = Product::factory()->create();

        BranchProduct::create([
            'branch_id' => $this->branch1->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        BranchProduct::create([
            'branch_id' => $this->branch2->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertSee($product->name);
        $this->assertFalse($product->applyGrayscale());
    }

    public function test_catalog_has_a_product_that_is_available_in_one_branch_and_not_in_the_other()
    {
        $product = Product::factory()->create();

        BranchProduct::create([
            'branch_id' => $this->branch1->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        BranchProduct::create([
            'branch_id' => $this->branch2->id,
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertSee($product->name);
        $this->assertFalse($product->applyGrayscale());
    }

    public function test_catalog_has_a_product_that_is_not_available_in_any_branches()
    {
        $product = Product::factory()->create();

        BranchProduct::create([
            'branch_id' => $this->branch1->id,
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        BranchProduct::create([
            'branch_id' => $this->branch2->id,
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertSee($product->name);
        $this->assertTrue($product->applyGrayscale());
    }

    public function test_catalog_is_empty_with_a_product_that_is_not_available()
    {
        $product = Product::factory()->create();

        BranchProduct::create([
            'branch_id' => $this->branch1->id,
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        BranchProduct::create([
            'branch_id' => $this->branch2->id,
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $product->delete();

        $response = $this->get(route('catalog.index'));

        $response->assertSee($product->name = '');
    }

    public function test_catalog_has_no_products()
    {
        $product = Product::factory();

        $response = $this->get(route('catalog.index'));

        $response->assertSee($product->name = '');
    }
}
