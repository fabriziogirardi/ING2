<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class GovernmentIdType extends Model
{
    /** @use HasFactory<\Database\Factories\GovernmentIdTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
