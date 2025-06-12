<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly
class ProductAvailabilityService
{
    public function __construct(
        private string $start,
        private string $end,
    ) {}

    public function getProductsWithAvailability(int $perPage = 15): LengthAwarePaginator
    {
        $paginated = Product::with([
            'branch_products' => function ($q) {
                $q->with([
                    'reservations' => function ($r) {
                        $r->where(function ($query) {
                            $query->whereBetween('start_date', [$this->start, $this->end])
                                ->orWhereBetween('end_date', [$this->start, $this->end])
                                ->orWhere(function ($q) {
                                    $q->where('start_date', '<=', $this->start)
                                        ->where('end_date', '>=', $this->end);
                                });
                        });
                    },
                    'branch',
                ]);
            },
        ])->paginate($perPage);

        $paginated->getCollection()->transform(function ($product) {
            return [
                'product'                => $product,
                'has_stock'              => $this->hasStock($product),
                'branches_with_stock'    => $this->branchesWithStock($product),
                'branches_without_stock' => $this->branchesWithoutStock($product),
            ];
        });

        return $paginated;
    }

    protected function hasStock(Product $product): bool
    {
        return $product->branch_products->contains(function ($bp) {
            return $bp->quantity > $bp->reservations->count();
        });
    }

    protected function branchesWithStock(Product $product): Collection
    {
        return $product->branch_products->filter(function ($bp) {
            return $bp->quantity > $bp->reservations->count();
        })->map(function ($bp) {
            return [
                'branch_id'   => $bp->branch->id,
                'branch_name' => $bp->branch->name,
                'available'   => $bp->quantity - $bp->reservations->count(),
            ];
        })->values();
    }

    protected function branchesWithoutStock(Product $product): Collection
    {
        return $product->branch_products->filter(function ($bp) {
            return $bp->quantity <= $bp->reservations->count();
        })->map(function ($bp) {
            return [
                'branch_id'   => $bp->branch->id,
                'branch_name' => $bp->branch->name,
                'available'   => 0,
            ];
        })->values();
    }
}
