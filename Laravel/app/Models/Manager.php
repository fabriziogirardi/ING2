<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
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
class Manager extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<\Database\Factories\ManagerFactory> */
    use HasFactory;

    protected $fillable = [
        'person_id',
        'password',
    ];

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
        $this->token()->delete();
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

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->person->full_name;
    }
}
