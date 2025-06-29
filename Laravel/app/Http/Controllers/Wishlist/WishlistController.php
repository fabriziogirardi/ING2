<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        return view('customer.wishlist.wishlist-index');
    }

    public function store(StoreWishlistRequest $request)
    {
        Wishlist::create([
            'customer_id' => auth()->id(),
            'name'        => $request->name,
        ]);

        return redirect()->back()->with('success', 'Lista creada.');
    }

    public function show(Wishlist $wishlist)
    {
        $wishlist->load('products.machine');

        return view('wishlist.show', compact('wishlist'));
    }


    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->products()->exists()) {
            return redirect()->back()->withErrors('La lista contiene maquinarias.');
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'Lista eliminada.');
    }
}
