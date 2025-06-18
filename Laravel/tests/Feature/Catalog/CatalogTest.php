<?php

namespace Tests\Feature\Catalog;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_requires_dates_to_show_availability()
    {
        $response = $this->get(route('catalog.index'));
        $response->assertSee(__('catalog/forms.select_dates_to_see_availability'));
    }

    public function test_catalog_shows_no_products_message_when_empty()
    {
        $response = $this->get(route('catalog.index', [
            'start' => now()->toDateString(),
            'end'   => now()->addDays(2)->toDateString(),
        ]));
        $response->assertSee(__('catalog/forms.no_products_available'));
    }

    //    public function test_product_card_shows_see_more_when_available_and_meets_min_days()
    //    {
    //        app()->setLocale('es'); // Set locale to Spanish
    //
    //        $branch = Branch::factory()->create();
    //        $product = Product::factory()->create();
    //        BranchProduct::create([
    //            'branch_id' => $branch->id,
    //            'product_id' => $product->id,
    //            'quantity' => 5,
    //        ]);
    //
    //        $response = $this->get(route('catalog.index', [
    //            'start' => now()->toDateString(),
    //            'end' => now()->addDays(3)->toDateString(),
    //        ]));
    //
    //        $response->assertSee(__('catalog/forms.see_more'));
    //    }
    //
    //    public function test_product_card_shows_min_days_message_when_not_meeting_min_days()
    //    {
    //
    //        $branch = Branch::factory()->create();
    //        $product = Product::factory()->create(['min_days' => 5]);
    //        BranchProduct::create([
    //            'branch_id' => $branch->id,
    //            'product_id' => $product->id,
    //            'quantity' => 5,
    //        ]);
    //
    //        $response = $this->get(route('catalog.index', [
    //            'start' => now()->toDateString(),
    //            'end' => now()->addDays(2)->toDateString(),
    //        ]));
    //
    //        $response->assertSee(__('catalog/forms.min_days_to_reserve', ['days' => 5]));
    //    }
    //
    //    public function test_product_card_shows_not_available_when_no_stock()
    //    {
    //        $branch = Branch::factory()->create();
    //        $product = Product::factory()->create();
    //        BranchProduct::create([
    //            'branch_id' => $branch->id,
    //            'product_id' => $product->id,
    //            'quantity' => 0,
    //        ]);
    //
    //        $response = $this->get(route('catalog.index', [
    //            'start' => now()->toDateString(),
    //            'end' => now()->addDays(2)->toDateString(),
    //        ]));
    //
    //        $response->assertSee(__('catalog/forms.not_available'));
    //    }
}
