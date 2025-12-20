<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::with('photo')->latest()->get();
    }

    public function getCategoryWithItems($id)
    {
        return Category::with(['photo', 'items' => function($query) {
            $query->where('is_shown_in_store', true)->with('image');
        }])->findOrFail($id);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }
}
