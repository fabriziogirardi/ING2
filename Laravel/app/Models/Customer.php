<?php

namespace App\Models;

use App\Mail\NewCustomerCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::saving(function (Customer $customer) {
            if ($customer->isDirty('person_id') && $customer->person_id && ! $customer->password) {
                $password = Str::password(8);

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

    public function scopeFindByGovernmentId(Builder $query, string $idNumber, int $idType): Builder
    {
        return $query->withTrashed()
            ->whereRelation('person', 'government_id_number', $idNumber)
            ->whereRelation('person.government_id_type', 'id', $idType);
    }
}
