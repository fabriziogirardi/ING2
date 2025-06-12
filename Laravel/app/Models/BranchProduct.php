<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class BranchProduct extends Pivot
{
    protected $table = 'branch_product';

    protected $fillable = [
        'branch_id',
        'product_id',
        'quantity',
    ];

    protected $with = [
        'reservations',
    ];

    public function reservations(): HasMany
    {
        return $this->HasMany(Reservation::class, 'branch_product_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
