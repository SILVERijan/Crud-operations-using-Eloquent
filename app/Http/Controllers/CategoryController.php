<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        // Admin can get access to all categories.
        $query = Category::query();
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $categories = $query->oldest()->paginate(5);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {                                                                                                                                                                                                                                                   
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();     
    
        $data['user_id'] = auth()->id();

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $data = $request->validated();

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
                                                                                                                                        