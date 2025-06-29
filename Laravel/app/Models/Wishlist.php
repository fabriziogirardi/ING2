<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function ($sublist) {
            $sublist->allItems()->delete(); // Delete all items, even if product is missing
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function allItems()
    {
        return $this->hasMany(\App\Models\WishlistProduct::class);
    }

    public function products()
    {
        return $this->hasMany(WishlistProduct::class)
            ->whereHas('product');
    }
}
