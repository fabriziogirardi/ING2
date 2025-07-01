<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CancelPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'requires_amount_input',
    ];


    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            CancelPolicyProduct::class,
            'cancel_policy_id',
            'id',
            'id',
            'product_id'
        );
    }

}
