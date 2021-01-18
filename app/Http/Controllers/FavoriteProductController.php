<?php

namespace App\Http\Controllers;

use Auth;
use App\Product;
use Illuminate\Http\Request;

class FavoriteProductController extends Controller
{
    /**
     * Retorna lista de produtos favoritos do cliente.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $customer = Auth::user();

        return $customer->favoriteProducts;
    }

    /**
     * Adicionar empresas aos favoritos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
           'product_uuid' => 'required|uuid|exists:products,uuid',
        ]);

        $customer = Auth::user();
        $favorite = $customer->favoriteProducts()->create(['product_uuid' => $request->input('product_uuid')]);
        if ($favorite) {
            return response()->json([
                'message' => __('common.save_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.save_fail')], 422);
    }

    /**
     * Remove produtos dos favoritos.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $customer = Auth::user();
        if ($customer->favoriteProducts()->where(['product_uuid' => $product->uuid])->delete()) {
            return response()->json(['message' => __('common.removed_success')], 200);
        }

        return response()->json(['message' => __('common.removed_fail')], 422);
    }
}
