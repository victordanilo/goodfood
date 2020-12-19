<?php

namespace App\Http\Controllers;

use Auth;
use App\Company;
use App\Helpers\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Retorna lista de empresas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Company::all();
    }

    /**
     *  Cria empresa.
     *
     * @param StoreCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'company/avatar/');
        }

        $company = Company::create($datas);
        if ($company) {
            return response()->json([
                'company' => $company,
                'message' => __('common.created_success'),
            ], 201);
        }

        return response()->json(['message' => __('common.created_fail')], 422);
    }

    /**
     * Exibe dados da empresa.
     *
     * @param Company $company
     * @return Company
     */
    public function show(Company $company)
    {
        return $company;
    }

    /**
     * Atualiza dados da empresa.
     *
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $datas = $request->validated();
        if (! empty($request->file('avatar'))) {
            $datas['avatar'] = Upload::img($request->file('avatar'), 'company/avatar/');
        }

        if ($company->update($datas)) {
            return response()->json([
                'updates' => $datas,
                'message' => __('common.updated_success'),
            ], 200);
        }

        return response()->json(['message' => __('common.updated_fail')], 422);
    }

    /**
     * Remove empresa.
     *
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        if ($company->delete()) {
            return response()->json(['message' => __('common.deleted_success')], 200);
        }

        return response()->json(['message' => __('common.deleted_fail')], 422);
    }

    /**
     * Criar credenciais do vendedor.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCredential(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/x-www-form-urlencoded',
        ])->post('https://api.mercadopago.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('mp.redirect_url'),
            'client_secret' => config('mp.access_token'),
            'code' => $request->input('code'),
        ]);

        if ($response->ok()) {
            $credential = $response->json();
            Auth::user()->credential()->create([
                'mp_user_id' => $credential['user_id'],
                'public_key' => $credential['public_key'],
                'access_token' => $credential['access_token'],
                'refresh_token' => $credential['refresh_token'],
                'expires_in' => $credential['expires_in'],
            ]);

            return response()->json(['message' => __('common.created_credential_success')], 201);
        }

        return response()->json(['message' => __('common.created_credential_fail')], 422);
    }
}
