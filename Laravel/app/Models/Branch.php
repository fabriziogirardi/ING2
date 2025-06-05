<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
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

    protected $fillable = ['place_id', 'name', 'address', 'latitude', 'longitude', 'description'];

    protected function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(BranchProduct::class)->withPivot('quantity')->withTimestamps();
    }
}
