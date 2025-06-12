<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    protected $fillable = [
        'customer_id',
        'branch_product_id',
        'code',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function branchProduct(): BelongsTo
    {
        return $this->belongsTo(BranchProduct::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function retired(): HasOne
    {
        return $this->hasOne(ReservationRetired::class);
    }

    public function returned(): HasOne
    {
        return $this->hasOne(ReservationReturned::class);
    }

    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start->diffInDays($this->end),
        );
    }
}
