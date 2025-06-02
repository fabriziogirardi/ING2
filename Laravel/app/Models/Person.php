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
class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    protected $with = ['government_id_type'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'government_id_type_id',
        'government_id_number',
        'birth_date',
    ];

    public function government_id_type(): BelongsTo
    {
        return $this->belongsTo(GovernmentIdType::class);
    }
}
