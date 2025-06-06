<?php

namespace App\Http\Controllers\Manager\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Model\StoreModelRequest;
use App\Http\Requests\Manager\Model\UpdateModelRequest;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index()
    {
        return view('manager.model.index', [
            'models' => ProductModel::paginate(10),
        ]);
    }

    public function create()
    {
        $brands = ProductBrand::select('id', 'name')->get()->map(function ($brand) {
            return [
                'id'   => $brand->id,
                'name' => $brand->name,
            ];
        })->toArray();

        return view('manager.model.create', compact('brands'));
    }

    public function store(StoreModelRequest $request)
    {
        $model = ProductModel::create($request->validated());

        return redirect()->route('manager.model.show', ['model' => $model->id])
            ->with('success', 'exito');
    }

    public function update(UpdateModelRequest $request, ProductModel $model)
    {
        $model->product_brand_id = $request->validated('product_brand_id');
        $model->name             = $request->validated('name');
        $model->save();

        return redirect()->to(route('manager.model.show', ['model' => $model->id]))->with('success', 'exito');
    }

    public function edit(ProductModel $model)
    {
        $brands = ProductBrand::select('id', 'name')->get()->map(function ($brand) {
            return [
                'id'   => $brand->id,
                'name' => $brand->name,
            ];
        })->toArray();

        return view('manager.model.edit', compact('model', 'brands'));
    }

    public function show(ProductModel $model)
    {
        return view('manager.model.show', [
            'model' => $model,
        ]);
    }

    public function destroy(Request $request, ProductModel $model)
    {
        $model->delete();

        return redirect()->to(route('manager.model.index'));
    }
}
