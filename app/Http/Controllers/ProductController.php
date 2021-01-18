<?php

namespace App\Http\Controllers;

use Auth;
use App\Product;
use App\Helpers\Upload;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Retorna produtos nos endpoit customer e admin.
     *
     * @return Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Product::with('category')->get();
    }

    /**
     * Retorna os produtos da empresa logada no endpoit company.
     *
     * @return mixed
     */
    public function indexCompany()
    {
        $company = Auth::user();

        return $company->products()->with('category')->get();
    }

    /**
     * Criar produtos da empresa logada.
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $company = Auth::user();
        $datas = $request->validated();
        if (! empty($request->file('image'))) {
            $datas['image'] = Upload::img($request->file('image'), 'product/');
        }

        $product = $company->products()->create($datas);
        if ($product) {
            return response()->json([
                'product' => $product,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Exibe dados do produto.
     *
     * @param Product $product
     * @return Product
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Atualiza dados do produto.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $datas = $request->validated();
        if (! empty($request->file('image'))) {
            $datas['image'] = Upload::profileImg($request->file('image'), 'product/');
        }

        if ($product->update($datas)) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove o produto.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
