<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductAvailabilityService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->input('start');
        $end_date   = $request->input('end');
        $products   = null;

        if ($start_date && $end_date) {
            session(['start_date' => $start_date, 'end_date' => $end_date]);
            $service = new ProductAvailabilityService($start_date, $end_date);
            $products = $service->getProductsWithAvailability();

            $diff = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));
            $products->getCollection()->transform(function ($item) use ($diff) {
                $minDays = $item['product']->min_days ?? 1;
                $item['meets_min_days'] = $diff >= $minDays;
                $item['min_days'] = $minDays;
                return $item;
            });
        } else {
            session()->forget(['start_date', 'end_date']);
        }

        return view('catalog.index', [
            'products'   => $products,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }

    public function show(Product $product)
    {
        $start_date = session('start_date');
        $end_date   = session('end_date');

        return view('catalog.show', [
            'product'    => $product,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ]);
    }
}
