<?php

namespace App\Http\Controllers;

use Auth;
use App\CompanyReview;
use App\Http\Requests\StoreCompanyReviewRequest;
use App\Http\Requests\UpdateCompanyReviewRequest;

class CompanyReviewController extends Controller
{
    /**
     * Retorna a lista de avaliação sobre as empresas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexCustomer()
    {
        $customer = Auth::user();

        return $customer->reviewCompanies;
    }

    /**
     * Retorna a lista de avaliação da empresa.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexCompany()
    {
        $company = Auth::user();

        return $company->reviews;
    }

    /**
     * Cria avaliação sobre a empresa.
     *
     * @param CompanyReviewRequest $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCompanyReviewRequest $request)
    {
        $customer = Auth::user();
        $review = $customer->reviewCompanies()->create($request->validated());
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
     * @param CompanyReview $review
     * @return CompanyReview
     */
    public function show(CompanyReview $review)
    {
        return $review;
    }

    /**
     * Atualiza avaliação sobre a empresa.
     *
     * @param CompanyReview $request
     * @param CompanyReview $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCompanyReviewRequest $request, CompanyReview $review)
    {
        if ($review->update($request->validated())) {
            return response()->json(['message' => __('common.updated_success')], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove avaliação sobre a empresa.
     *
     * @param CompanyReview $review
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(CompanyReview $review)
    {
        if ($review->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }
}
