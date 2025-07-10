<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class ForumDiscussion extends Model
{
    /** @use HasFactory<\Database\Factories\ForumDiscussionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'customer_id',
        'forum_section_id',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(ForumSection::class, 'forum_section_id');
    }
}
