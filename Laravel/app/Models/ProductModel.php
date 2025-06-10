<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class ProductModel extends Model
{
    use HasFactory, SoftDeletes;

    /** @use HasFactory<\Database\Factories\ProductModelFactory> */
    protected $fillable = [
        'product_brand_id',
        'name',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class);
    }
}
