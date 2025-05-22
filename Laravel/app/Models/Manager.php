<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Random\RandomException;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Manager extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\ManagerFactory> */
    use HasFactory;

    protected $with = ['person'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function token(): HasOne
    {
        return $this->hasOne(ManagerToken::class);
    }

    public function deleteTokens(): void
    {
        $this->tokens()->delete();
    }

    /**
     * @throws RandomException
     */
    public function createToken(): void
    {
        if ($this->token()->exists()) {
            $this->deleteTokens();
        }

        $this->token()->create([
            'token'      => random_int(10000000, 99999999),
            'expires_at' => now()->addMinutes(2),
        ]);
    }
}
