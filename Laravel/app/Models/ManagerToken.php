<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class ManagerToken extends Model
{
    /** @use HasFactory<\Database\Factories\ManagerTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'token',
        'expires_at',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
