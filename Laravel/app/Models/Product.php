<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_model_id',
        'price',
        'min_days',
    ];

    protected $with = ['categories'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->without('children');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class)->using(BranchProduct::class)->withPivot('quantity')->withTimestamps();
    }

    #[Scope]
    protected function get_all_by_category(EloquentBuilder $query, Category $category): void
    {
        $query->whereHas('categories', function (EloquentBuilder $query) use ($category) {
            $query->whereIn('categories.id', $category->all_children);
        })->without('categories');
    }
}
