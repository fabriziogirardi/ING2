<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreItemRequest;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\WishlistSublist;
use Carbon\Carbon;

class WishlistItemController extends Controller
{
    public function index(Wishlist $wishlist, WishlistSublist $subwishlist)
    {
        $subwishlist->load('items');

        return view('customer.wishlist.itemswishlist-index', ['subwishlist' => $subwishlist]);
    }

    public function store(StoreItemRequest $request)
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate   = Carbon::parse($request->end_date)->format('Y-m-d');

        $exists = WishlistItem::where('wishlist_sublist_id', $request->wishlist_sublist_id)
            ->where('product_id', $request->product_id)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->exists();

        if ($exists) {
            return redirect()->back()->with(['toast' => 'danger', 'message' => 'Ya existe una maquinaria guardada con esas fechas.']);
        }

        $sublist = WishlistSublist::findOrFail($request->input('wishlist_sublist_id'));
        $sublist->items()->create($request->validated());

        return redirect()->back()->with(['toast' => 'success', 'message' => 'Maquinaria guardada en tu lista de deseos.']);
    }

    public function destroy(WishlistItem $item)
    {
        $item->delete();

        return redirect()->back()->with('success', 'Producto eliminado.');
    }
}
