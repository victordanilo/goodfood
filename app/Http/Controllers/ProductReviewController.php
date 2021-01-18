<?php

namespace App\Http\Controllers;

use Auth;
use App\Product;
use App\ProductReview;
use App\Http\Requests\StoreProductReviewRequest;
use App\Http\Requests\UpdateProductReviewRequest;

class ProductReviewController extends Controller
{
    /**
     * Retorna a lista de avaliação sobre os produtos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexCustomer()
    {
        $customer = Auth::user();

        return $customer->reviewProducts;
    }

    /**
     * Retorna a lista de avaliação do produto.
     *
     * @param Product $product
     */
    public function indexProduct(Product $product)
    {
        return $product->reviews;
    }

    /**
     * Cria avaliação sobre o produto.
     *
     * @param StoreProductReviewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductReviewRequest $request)
    {
        $customer = Auth::user();
        $review = $customer->reviewProducts()->create($request->validated());
        if ($review) {
            return response()->json([
                'message' => __('common.save_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.save_fail')], 422);
    }

    /**
     * Exibe detalhes da avaliação dada pelo cliente.
     *
     * @param ProductReview $review
     * @return ProductReview
     */
    public function show(ProductReview $review)
    {
        return $review;
    }

    /**
     * Atualiza avaliação sobre o produto.
     *
     * @param UpdateProductReviewRequest $request
     * @param ProductReview $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductReviewRequest $request, ProductReview $review)
    {
        if ($review->update($request->validated())) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove avaliação sobre o produto.
     *
     * @param ProductReview $review
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(ProductReview $review)
    {
        if ($review->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
