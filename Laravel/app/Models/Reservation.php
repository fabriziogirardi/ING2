<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'product_id',
        'code',
        'start',
        'end',
    ];
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start->diffInDays($this->end),
        );
    }
}
