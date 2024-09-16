<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;

class ApiController extends Controller
{
    public function getCategories(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $categories = Category::with('products')->paginate($perPage);
        return CategoryResource::collection($categories)
            ->additional([
                'meta' => [
                    'total' => $categories->total(),
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'last_page' => $categories->lastPage(),
                    'total_pages' => $categories->lastPage(),
                ]
            ]);
    }
    public function getCategory($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return new CategoryResource($category);
    }
    public function getProducts(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $products = Product::with('categories')->paginate($perPage);
        return ProductResource::collection($products)
            ->additional([
                'meta' => [
                    'total' => $products->total(),
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'last_page' => $products->lastPage(),
                    'total_pages' => $products->lastPage(),
                ]
            ]);
    }
    public function getProduct($id)
    {
        $product = Product::with(['categories', 'images'])->findOrFail($id);
        return new ProductResource($product);
    }
}
