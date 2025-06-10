<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class ProductModel extends Model
{
    use HasFactory;
    use softDeletes;

    /** @use HasFactory<\Database\Factories\ProductModelFactory> */
    protected $fillable = [
        'product_brand_id',
        'name',
    ];

    protected $with = [
        'product_brand',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(static function (ProductModel $model) {
            $model->products()->delete();
        });

        static::restoring(static function (ProductModel $model) {
            $model->products()->withTrashed()->restore();
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function product_brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_model_id');
    }
}
