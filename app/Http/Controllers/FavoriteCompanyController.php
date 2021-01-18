<?php

namespace App\Http\Controllers;

use Auth;
use App\Company;
use Illuminate\Http\Request;

class FavoriteCompanyController extends Controller
{
    /**
     * Retorna lista de empresas favoritas do cliente.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $customer = Auth::user();
        $companies = $customer->favoriteCompanies->map(function ($favorite) {
            return $favorite->company->uuid;
        });

        return  $companies;
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
            'company_uuid' => 'required|uuid|exists:companies,uuid',
        ]);

        $company = Company::where(['uuid' => $request->input('company_uuid')])->first();
        $customer = Auth::user();
        $favorite = $customer->favoriteCompanies()->create(['company_id' => $company->id]);
        if ($favorite) {
            return response()->json([
                'message' => __('common.save_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.save_fail')], 422);
    }

    /**
     * Remove empresas dos favoritos.
     *
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Company $company)
    {
        $customer = Auth::user();
        if ($customer->favoriteCompanies()->where(['company_id' => $company->id])->delete()) {
            return response()->json(['message' => __('common.removed_success')], 200);
        }

        return response()->json(['message' => __('common.removed_fail')], 422);
    }
}
