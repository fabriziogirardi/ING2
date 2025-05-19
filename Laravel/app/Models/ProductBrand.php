<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class ProductBrand extends Model
{
    /** @use HasFactory<\Database\Factories\ProductBrandFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
