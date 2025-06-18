<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $today      = Carbon::today()->format('m/d/Y');
        $start_date = $request->input('start_date') ?? $today;
        $end_date   = $request->input('end_date') ?? Carbon::today()->addDays(3)->format('m/d/Y');

        session(['start_date' => $start_date, 'end_date' => $end_date]);

        $service = new ProductAvailabilityService($start_date, $end_date);

        $products = $service->getProductsWithAvailability();

        return view('catalog.index', [
            'products'   => $products,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }

    public function show(Product $product)
    {
        $start_date = session('start_date') ?? now()->toDateString();
        $end_date   = session('end_date') ?? now()->addDays(3)->toDateString();
        $today      = Carbon::today()->format('m/d/Y');

        return view('catalog.show', [
            'product'             => $product,
            'branches_with_stock' => $product->branchesWithStockBetween($start_date, $end_date),
            'start_date'          => $start_date,
            'end_date'            => $end_date,
            'today'               => $today,
            'wishlists'           => \Illuminate\Support\Facades\Auth::guard('customer')->user()
                                    ->wishlists()
                                    ->with('sublists:id,wishlist_id,name')
                                    ->get(['id', 'name']),
        ]);
    }
}
