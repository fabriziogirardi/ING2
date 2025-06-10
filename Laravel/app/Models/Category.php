<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    protected $appends = [
        'fully_qualified_name',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(static function ($model) {
            $model->slug = Str::slug("$model->id $model->name");
            $model->saveQuietly();
        });

        static::saved(static function ($model) {
            $model->slug = Str::slug("$model->id $model->name");
            $model->saveQuietly();
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function parents(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id')
            ->without('children')
            ->with('parents');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function allChildren(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ids = collect([$this->id]);

                return $ids->merge($this->children->pluck('id')->merge(
                    $this->children->flatMap(fn ($child) => $child->all_children),
                ))->sort();
            },
        );
    }

    public function allParents(): Attribute
    {
        return Attribute::make(
            get: function () {
                $parents = new Collection;

                if ($this->parent) {
                    $parents = $parents->merge($this->parents->all_parents);
                    $parents->unshift($this->parent);
                }

                return $parents;
            },
        );
    }

    public function fullyQualifiedName(): Attribute
    {
        return Attribute::make(
            get: function () {

                if ($this->parent) {
                    $name = $this->parent->fully_qualified_name.' / '.$this->name;
                } else {
                    $name = $this->name;
                }

                return $name;
            },
        );
    }

    #[Scope]
    public function roots(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereNull('parent_id');
    }

    #[Scope]
    public function leaves(EloquentBuilder $query)
    {
        return $query->doesntHave('children')->get();
    }
}
