<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class CancelPolicyProduct extends Model
{
    protected $table = 'cancel_policy_product';

    protected $fillable = [
        'product_id',
        'cancel_policy_id'
    ];

    public function cancelPolicy(): BelongsTo
    {
        return $this->belongsTo(CancelPolicy::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

