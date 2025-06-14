<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(10);

        $start_date = $request->input('start', now()->format('d-m-Y'));
        $end_date   = $request->input('end', now()->addDays(Product::min('products.min_days'))->format('d-m-Y'));

        session(['start_date' => $start_date, 'end_date' => $end_date]);

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
