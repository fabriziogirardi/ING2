<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    use softDeletes;

    protected $fillable = [
        'name',
        'description',
        'product_model_id',
        'price',
        'min_days',
        'images_json',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'min_days'    => 'integer',
        'images_json' => 'array',
    ];

    protected $with = ['categories', 'product_model', 'product_model.brand'];

    public function product_model(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)
            ->withPivot('quantity')
            ->using(BranchProduct::class)
            ->as('stock');
    }

    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, BranchProduct::class);
    }

    public function branch_products(): HasMany
    {
        return $this->hasMany(BranchProduct::class);
    }

    #[Scope]
    protected function get_all_by_category(EloquentBuilder $query, Category $category): EloquentBuilder
    {
        return $query->whereHas('categories', function (EloquentBuilder $query) use ($category) {
            $query->whereIn('categories.id', $category->all_children);
        })->without('categories');
    }
    
    public function branchesWithStockBetween(string $start, string $end): array
    {
        return $this->branch_products
            ->filter(function ($bp) use ($start, $end) {
                $reservationsCount = $bp->reservations()
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_date', [$start, $end])
                            ->orWhereBetween('end_date', [$start, $end])
                            ->orWhere(function ($q) use ($start, $end) {
                                $q->where('start_date', '<=', $start)
                                    ->where('end_date', '>=', $end);
                            });
                    })
                    ->count();
                
                return $bp->quantity > $reservationsCount;
            })
            ->pluck('branch.name', 'branch_id')
            ->toArray();
    }
}
