<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);
        try {
            $category = new Category();
            $category->name = $request->input('name');
            $category->save();
            return ['code' => 0, 'message' => __("Category created."), 'data' => $category->toArray()];
        } catch (\Exception $e) {
            return ['code' => 1, 'message' => $e->getMessage()];
        }
    }

    public function destroy(Category $category)
    {
        try {
            $categoryName = $category->name();
            $category->delete();
            return ['code' => 0, 'message' => __("Category :category deleted.", ['category' => $categoryName])];
        } catch (\Exception $e) {
            return ['code' => 1, 'message' => $e->getMessage()];
        }
    }
}
