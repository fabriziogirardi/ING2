<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\ManagerFactory> */
    use HasFactory;
    
    protected $with = ['person'];
    
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
