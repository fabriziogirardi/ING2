<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreItemRequest;
use App\Models\Wishlist;
use App\Models\WishlistProduct;
use Carbon\Carbon;

class WishlistProductController extends Controller
{
    public function index(Wishlist $wishlist)
    {
        $wishlist->load('products');

        return view('customer.wishlist.products-wishlist-index', ['wishlist' => $wishlist]);
    }

    public function store(StoreItemRequest $request)
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate   = Carbon::parse($request->end_date)->format('Y-m-d');

        $exists = WishlistProduct::where('wishlist_id', $request->wishlist_id)
            ->where('product_id', $request->product_id)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->exists();

        if ($exists) {
            return redirect()->back()->with(['toast' => 'danger', 'message' => 'Ya existe una maquinaria guardada con esas fechas.']);
        }

        $sublist = Wishlist::findOrFail($request->input('wishlist_id'));
        $sublist->products()->create($request->validated());

        return redirect()->back()->with(['toast' => 'success', 'message' => 'Maquinaria guardada en tu lista de deseos.']);
    }

    public function destroy(WishlistProduct $item)
    {
        $item->delete();

        return redirect()->back()->with('success', 'Maquinaria eliminado.');
    }
}
