<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Refund extends Model
{
    protected $fillable = [
        'reservation_id',
        'amount',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id')->withTrashed();
    }
}
