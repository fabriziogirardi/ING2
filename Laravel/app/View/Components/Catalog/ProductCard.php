<?php

namespace App\View\Components\Catalog;

use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public bool $meetsMinDays;

    public Product $product;

    public $wishlists;

    public function __construct(
        public array $productData,
        public string $startDate,
        public string $endDate,
    ) {
        $this->meetsMinDays = Carbon::parse($startDate)
            ->diffInDays(Carbon::parse($endDate)) + 1 >= $productData['product']->min_days;
        $this->product = $productData['product'];
        $branchId      = session('branch_id');
        if (Auth::getCurrentGuard() === 'customer') {
            $this->wishlists = \Illuminate\Support\Facades\Auth::guard('customer')->user()
                ->wishlists()
                ->with('sublists:id,wishlist_id,name')
                ->get(['id', 'name']);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.catalog.product-card');
    }
}
