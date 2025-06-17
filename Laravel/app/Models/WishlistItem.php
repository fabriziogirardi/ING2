<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'wishlist_sublist_id',
        'product_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function sublist()
    {
        return $this->belongsTo(WishlistSublist::class, 'wishlist_sublist_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

