<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index(Wishlist $wishlist)
    {
        $wishlist->load('sublists');

        return view('customer.wishlist.subwishlist-index', ['wishlist' => $wishlist]);
    }

    public function store(StoreWishlistRequest $request)
    {
        Wishlist::create([
            'customer_id' => auth()->id(),
            'name'        => $request->name,
        ]);

        return redirect()->back()->with('success', 'Lista creada.');
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->sublists()->exists()) {
            return redirect()->back()->withErrors('La lista no está vacía.');
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'Lista eliminada.');
    }
}
