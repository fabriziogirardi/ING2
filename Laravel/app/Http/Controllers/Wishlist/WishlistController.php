<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Models\WishlistSublist;
use Illuminate\Http\Response;

class WishlistController extends Controller
{
    public function index()
    {
        return view('customer.wishlist-list.index', [
            'wishlists' => Wishlist::paginate(10),
        ]);
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
