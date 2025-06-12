<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'images_json',
    ];

    protected $with = ['categories'];

    public function hasStock(): bool
    {
        // Implement logic to determine if the product should be displayed in grayscale

        // Implementacion momentanea para simular el comportamiento
        return rand(0, 1) === 1;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->without('children');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)->using(BranchProduct::class)->withPivot('quantity')->withTimestamps();
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }

    #[Scope]
    protected function get_all_by_category(EloquentBuilder $query, Category $category): void
    {
        $query->whereHas('categories', function (EloquentBuilder $query) use ($category) {
            $query->whereIn('categories.id', $category->all_children);
        })->without('categories');
    }
}
