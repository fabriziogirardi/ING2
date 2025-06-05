<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class BranchProduct extends Pivot
{
    use HasFactory, SoftDeletes;

    /** @use HasFactory<\Database\Factories\BranchProductFactory> */
    protected $fillable = [
        'product_id',
        'branch_id',
        'quantity',
    ];
}
