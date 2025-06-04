<?php

namespace App\Http\Controllers\Manager\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductBrand;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('manager.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = ProductBrand::all();
        return view('manager.product.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('manager.product.create')->with('success', __('manager/product.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('manager.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('manager.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('manager.product.show',['product' => $product->id])->with('success',__('manager/product.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('manager.product.index')->with('success',__('manager/product.deleted'));
    }
}
