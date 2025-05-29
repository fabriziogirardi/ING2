<?php

namespace App\Http\Controllers\Manager\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Category\StoreCategoryRequest;
use App\Http\Requests\Manager\Category\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.category.index', [
            'categories' => Category::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name'        => $request->validated('name'),
            'description' => $request->validated('description'),
        ]);

        return redirect()->route('manager.category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('manager.category.show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('manager.category.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name'        => $request->validated('name'),
            'description' => $request->validated('description'),
        ]);

        return redirect()->route('manager.category.show', ['category' => $category]);
    }
}
