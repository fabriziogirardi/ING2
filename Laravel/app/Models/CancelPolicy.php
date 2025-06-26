<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'requires_amount_input',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
