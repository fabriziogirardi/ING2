<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

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
            ->withPivot('quantity')
            ->using(BranchProduct::class)
            ->as('stock');
    }

    public function defaultLocation(): Attribute
    {
        return Attribute::make(
            get: fn () => [$this->latitude, $this->longitude],
        );
    }
}
