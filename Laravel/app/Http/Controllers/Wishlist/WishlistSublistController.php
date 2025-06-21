<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreSublistRequest;
use App\Models\Wishlist;
use App\Models\WishlistSublist;

class WishlistSublistController extends Controller
{
    public function index(WishlistSublist $subwishlist)
    {
        $subwishlist->load('items');
        dd($subwishlist);
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
