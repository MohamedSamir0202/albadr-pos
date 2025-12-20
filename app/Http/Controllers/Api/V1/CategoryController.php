<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoryRequest;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->responseApi($categories);
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryWithItems($id);
            return $this->responseApi($category);
        } catch (\Exception $e) {
            return $this->apiErrorMessage('Category not found', 404);
        }
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->validated());
        return $this->responseApi($category, 'Category created successfully');
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = $this->categoryService->updateCategory($id, $request->validated());
        return $this->responseApi($category, 'Category updated successfully');
    }

    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return $this->responseApi(null, 'Category deleted successfully');
    }
}
