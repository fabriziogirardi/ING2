<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function government_id_type(): BelongsTo
    {
        return $this->belongsTo(GovernmentIdType::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->first_name.' '.$this->last_name)->title(),
        );
    }

    public function fullNameEllipsis(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->first_name.' '.$this->last_name)->title()->limit(18),
        );
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => str(mb_substr($this->first_name, 0, 1).mb_substr($this->last_name, 0, 1))->upper(),
        );
    }

    public function fullIdNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->government_id_type->name.' '.$this->government_id_number,
        );
    }
}
