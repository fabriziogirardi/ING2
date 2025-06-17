<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistSublist;
use App\Http\Requests\Wishlist\StoreSublistRequest;

class WishlistSublistController extends Controller
{
    public function index(Wishlist $wishlist)
    {
        return view('customer.wishlist-sublist.index', [
            'sublists' => $wishlist->sublists()->get(),
            'wishlist' =>  $wishlist,
        ]);
    }
    public function store(Wishlist $wishlist, StoreSublistRequest $request)
    {
        $wishlist->sublists()->create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Sublista creada.');
    }

    public function show(WishlistSublist $sublist)
    {
        $sublist->load('items.machine');
        return view('sublists.show', compact('sublist'));
    }

    public function destroy(WishlistSublist $sublist)
    {
        if ($sublist->items()->exists()) {
            return redirect()->back()->withErrors('La sublista contiene productos.');
        }

        $sublist->delete();
        return redirect()->back()->with('success', 'Sublista eliminada.');
    }
}
