<?php

namespace App\Http\Controllers\Manager\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Brand\StoreBrandRequest;
use App\Http\Requests\Manager\Brand\UpdateBrandRequest;
use App\Models\ProductBrand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function store(StoreBrandRequest $request)
    {
        // Create a new brand
        ProductBrand::create([
            'name' => $request->validated('name'),
        ]);

        return redirect()->to(route('manager.product.brand.index'))->with('success', 'exito');
    }

    public function update(UpdateBrandRequest $request, ProductBrand $brand)
    {
        $brand->name = $request->validated('name');
        $brand->save();

        return response(200);
    }

    public function destroy(Request $request, ProductBrand $brand)
    {
        // Delete the brand
        $brand->delete();

        return redirect()->to(route('manager.product.brand.index'))->with('success', 'exito');
    }
}
