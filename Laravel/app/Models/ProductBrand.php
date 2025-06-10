<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class ProductBrand extends Model
{
    /** @use HasFactory<\Database\Factories\ProductBrandFactory> */
    use HasFactory, SoftDeletes;

    use softDeletes;

    protected $fillable = [
        'name',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(static function (ProductBrand $brand) {
            $brand->models()->delete();
        });

        static::restoring(static function (ProductBrand $brand) {
            $brand->models()->withTrashed()->restore();
        });
    }

    public function models(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }
}
