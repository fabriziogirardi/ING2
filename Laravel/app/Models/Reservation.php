<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        'total_amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function branch_product(): BelongsTo
    {
        return $this->belongsTo(BranchProduct::class)->withTrashed();
    }

    public function product(): BelongsTo
    {
        return $this->branch_product->product()->withTrashed();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function retired(): HasOne
    {
        return $this->hasOne(RetiredReservation::class);
    }

    public function returned(): HasOne
    {
        return $this->hasOne(ReturnedReservation::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'reservation_id');
    }

    public function branch(): HasOneThrough
    {
        return $this->hasOneThrough(
            Branch::class,
            BranchProduct::class,
            'id',
            'id',
            'branch_product_id',
            'branch_id'
        );
    }

    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start->diffInDays($this->end),
        );
    }
}
