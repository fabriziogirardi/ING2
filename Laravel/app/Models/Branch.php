<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory, SoftDeletes;

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (Branch $instance) {
            $instance->branch_products()->each(function (BranchProduct $product) {
                $product->delete();
            });
        });

        // static::restoring(function (Branch $instance) {
        //    $instance->products()->withTrashed()->each(function ($product) {
        //        $product->pivot->restore();
        //    });
        // });
    }

    protected $fillable = [
        'place_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'description',
    ];

    protected $casts = [
        'latitude'         => 'float',
        'longitude'        => 'float',
        'default_location' => 'array',
    ];

    protected $appends = [
        'default_location',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('id', 'quantity')
            ->using(BranchProduct::class)
            ->wherePivot('deleted_at', null)
            ->as('stock');
    }

    public function branch_products(): HasMany
    {
        return $this->hasMany(BranchProduct::class, 'branch_id', 'id');
    }

    public function defaultLocation(): Attribute
    {
        return Attribute::make(
            get: fn () => [$this->latitude, $this->longitude],
        );
    }
}
