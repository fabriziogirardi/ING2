<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'person_id',
        'password',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function scopeFindByGovernmentId(Builder $query, string $idNumber, int $idType): Builder
    {
        return $query->withTrashed()
            ->whereRelation('person', 'government_id_number', $idNumber)
            ->whereRelation('person.government_id_type', 'id', $idType);
    }
}
