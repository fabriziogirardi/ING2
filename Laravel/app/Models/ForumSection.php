<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class ForumSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'forum_sections';

    public function discussions(): HasMany
    {
        return $this->hasMany(ForumDiscussion::class, 'forum_section_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($section) {
            if ($section->discussions()->exists()) {
                return false; // Cancels the delete operation
            }
        });
    }
}
