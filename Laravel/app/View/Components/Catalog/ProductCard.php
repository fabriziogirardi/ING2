<?php

namespace App\View\Components\Catalog;

use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public bool $meetsMinDays;

    public Product $product;

    public bool $hasStockInBranch;

    public function __construct(
        public array $productData,
        public string $startDate,
        public string $endDate,
    ) {
        $this->meetsMinDays = Carbon::parse($startDate)
            ->diffInDays(Carbon::parse($endDate)) + 1 >= $productData['product']->min_days;
        $this->product = $productData['product'];
        $branchId = session('branch_id');
        $this->hasStockInBranch = $this->productData['branches_with_stock']
            ->contains(fn($branch) => $branch['branch_id'] == $branchId && $branch['available'] > 0);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.catalog.product-card');
    }
}
