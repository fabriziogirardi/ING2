<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $wishlist = '';
        if (Auth::getCurrentGuard() === 'customer') {
            $wishlist = \Illuminate\Support\Facades\Auth::guard('customer')->user()
                ->wishlists()
                ->get(['id', 'name']);
        }

        return view('catalog.index', [
            'products'   => $products,
            'wishlists'  => $wishlist,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }

    public function show(Product $product)
    {
        $start_date = session('start_date') ?? now()->toDateString();
        $end_date   = session('end_date') ?? now()->addDays(3)->toDateString();
        $today      = Carbon::today()->format('m/d/Y');
        $wishlist   = '';
        if (Auth::getCurrentGuard() === 'customer') {
            $wishlist = \Illuminate\Support\Facades\Auth::guard('customer')->user()
                ->wishlists()
                ->get(['id', 'name']);
        }

        return view('catalog.show', [
            'product'             => $product,
            'branches_with_stock' => $product->branchesWithStockBetween($start_date, $end_date),
            'start_date'          => $start_date,
            'end_date'            => $end_date,
            'today'               => $today,
            'wishlists'           => $wishlist,
        ]);
    }
}
