<?php

namespace App\Http\Controllers\Manager\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Products\StoreBranchProductRequest;
use App\Http\Requests\Manager\Products\UpdateBranchProductRequest;
use App\Models\BranchProduct;
use Illuminate\Http\RedirectResponse;

class BranchProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(StoreBranchProductRequest $request)
    {
        BranchProduct::create([
            'branch_id'  => $request->validated('branch_id'),
            'product_id' => $request->validated('product_id'),
            'quantity'   => $request->validated('quantity'),
        ]);

        return redirect()->to(route('manager.products.index'));
    }

    /**
     * Update a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(UpdateBranchProductRequest $request, $product_id, $branch_id)
    {
        $branchProduct = BranchProduct::where('product_id', $product_id)
            ->where('branch_id', $branch_id)
            ->firstOrFail();

        $branchProduct->update([
            'quantity' => $request->validated('quantity'),
        ]);

        return redirect()->route('manager.products.show', ['product_id' => $product_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BranchProduct  $branchProduct
     * @return RedirectResponse
     */
    public function destroy($branch_id, $product_id)
    {
        BranchProduct::where('product_id', $product_id)
            ->where('branch_id', $branch_id)
            ->delete();

        return redirect()->to(route('manager.products.index'));
    }
}
