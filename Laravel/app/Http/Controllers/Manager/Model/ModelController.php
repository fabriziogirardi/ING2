<?php

namespace App\Http\Controllers\Manager\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Model\StoreModelRequest;
use App\Http\Requests\Manager\Model\UpdateModelRequest;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $models = ProductModel::with('brand')->get();

        return view('Manager.model.index', [
            'models' => $models,
        ]);
    }
    public function store(StoreModelRequest $request)
    {

        ProductModel::create([
            'name' => $request->validated('name'),
            'product_brand_id' => $request->validated('product_brand_id'),
        ]);

        return redirect()->back()->with([__('manager.model.created')]);
    }

    public function update(UpdateModelRequest $request, ProductModel $model)
    {
        $model->product_brand_id = $request->validated('product_brand_id');
        $model->name = $request->validated('name');
        $model->save();

        return redirect()->back();
    }

    public function destroy(Request $request, ProductModel $model)
    {
        $model->delete();

        return redirect()->to(route('manager.model.index'));
    }
}
