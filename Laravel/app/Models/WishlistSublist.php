<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistSublist extends Model
{
    use HasFactory;

    protected $fillable = [
        'wishlist_id',
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function ($sublist) {
            $sublist->allItems()->delete(); // Delete all items, even if product is missing
        });
    }

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function allItems()
    {
        return $this->hasMany(\App\Models\WishlistItem::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\WishlistItem::class)
            ->whereHas('product');
    }
}
