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

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }
}
