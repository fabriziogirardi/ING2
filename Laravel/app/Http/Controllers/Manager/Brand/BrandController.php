<?php

namespace App\Http\Controllers\Manager\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Brand\StoreBrandRequest;
use App\Http\Requests\Manager\Brand\UpdateBrandRequest;
use App\Models\ProductBrand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return view('manager.brand.index', [
            'brands' => ProductBrand::withTrashed()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('manager.brand.create');
    }

    public function store(StoreBrandRequest $request)
    {
        // Create a new brand
        ProductBrand::create([
            'name' => $request->validated('name'),
        ]);

        return redirect()->to(route('manager.brand.index'))->with('success', 'exito');
    }

    public function update(UpdateBrandRequest $request, ProductBrand $brand)
    {
        $brand->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->to(route('manager.brand.show', ['brand' => $brand->id]))->with('success', 'exito');
    }

    public function edit(ProductBrand $brand)
    {
        return view('manager.brand.edit', [
            'brand' => $brand,
        ]);
    }

    public function show(ProductBrand $brand)
    {
        return view('manager.brand.show', [
            'brand' => $brand,
        ]);
    }

    public function destroy(Request $request, ProductBrand $brand)
    {
        // Delete the brand
        $brand->delete();

        return redirect()->to(route('manager.brand.index'))->with('success', 'exito');
    }

    public function restore(string $id)
    {
        ProductBrand::withTrashed()->findOrFail($id)->restore();

        return redirect()->to(route('manager.brand.index'));
    }
}
