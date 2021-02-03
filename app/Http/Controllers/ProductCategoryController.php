<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Retorna lista de categorias de produtos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return ProductCategory::all();
    }

    /**
     * Criar categoria de produto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', ProductCategory::class);

        $request->validate([
            'name' => 'required|string|min:3|max:255|unique:product_categories',
        ]);

        $category = ProductCategory::create([
            'name' => $request->input('name'),
        ]);
        if ($category) {
            return response()->json([
                'category' => $category,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Atualiza categoria de produto.
     *
     * @param Request $request
     * @param ProductCategory $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, ProductCategory $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|min:3|max:255|unique:product_categories',
        ]);

        if ($category->update(['name' => $request->input('name')])) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove categoria de produto.
     *
     * @param ProductCategory $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ProductCategory $category)
    {
        $this->authorize('delete', $category);

        if ($category->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
