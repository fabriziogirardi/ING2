<?php

namespace App\Models;

use App\Mail\NewCustomerCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property float $rating
 * @property int $reservations_count
 */
class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected $appends = [
        'has_penalization',
    ];

    protected $fillable = [
        'person_id',
        'password',
        'rating',
        'reservations_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::saving(function (Customer $customer) {
            if ($customer->isDirty('person_id') && $customer->person_id && ! $customer->password) {
                $password = Str::password(length: 8, symbols: false);

                Mail::to($customer->person->email)->send(
                    new NewCustomerCreated(
                        $customer->person->first_name,
                        $customer->person->last_name,
                        $customer->person->email,
                        $password
                    )
                );

                $customer->password = Hash::make($password);
                $customer->saveQuietly();
            }
        });

        static::created(function (Customer $record) {
            // $record->password = Str::password(8);
            //
            // Mail::to($record->person->email)->send(
            //    new NewCustomerCreated(
            //        $record->person->first_name,
            //        $record->person->last_name,
            //        $record->person->email,
            //        $record->password
            //    )
            // );
            //
            // $record->update([
            //    'password' => Hash::make($record->password),
            // ]);
        });
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function wishlists(): HasMany
    {
        return $this->HasMany(Wishlist::class);
    }

    public function reservations(): HasMany
    {
        return $this->HasMany(Reservation::class);
    }

    public function coupon(): HasOne
    {
        return $this->hasOne(Coupon::class);
    }

    public function scopeFindByGovernmentId(Builder $query, string $idNumber, int $idType): Builder
    {
        return $query->withTrashed()
            ->whereRelation('person', 'government_id_number', $idNumber)
            ->whereRelation('person.government_id_type', 'id', $idType);
    }

    public function hasPenalization(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $lastReservation = $this->reservations()->latest()->first();

                if (! $lastReservation) {
                    return false;
                }

                return $this->reservations()
                    ->whereHas('returned', function ($query) use ($lastReservation) {
                        $query->where('created_at', '>', $lastReservation->created_at)
                            ->whereColumn('created_at', '>', 'reservations.end_date');
                    })
                    ->exists();
            }
        );
    }
}
