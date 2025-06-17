<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistSublist;
use App\Models\WishlistItem;
use App\Http\Requests\Wishlist\StoreItemRequest;

class WishlistItemController extends Controller
{
    public function index(Wishlist $wishlist, WishlistSublist $sublist)
    {
        return view('customer.wishlist-item.index', [
            'items' => $sublist->items()->get(),
        ]);
    }
    public function store(WishlistSublist $sublist, StoreItemRequest $request)
    {
        $sublist->items()->create($request->validated());
        return redirect()->back()->with('success', 'Producto agregado.');
    }

    public function show(WishlistItem $item)
    {
        $item->load('product');
        return view('items.show', compact('item'));
    }

    public function destroy(WishlistItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Producto eliminado.');
    }
}
