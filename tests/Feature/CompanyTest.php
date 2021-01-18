<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class CompanyTest extends TestCase
{
    public function testValidationCreateCompany()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'name' => 'Company Test ME',
            'cpf_cnpj' => '87379532000160',
            'email' => 'companytest@email.com',
            'password' => '123456',
            'delivery_cost' => '6,20',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/company', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);
    }

    public function testCreateCompany()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'name' => 'Company Test ME',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'companytest@email.com',
            'password' => '123456',
            'delivery_cost' => '6.20',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/company', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateCompany()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $company = Company::create([
            'name' => 'Company Test ME',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'companytest@email.com',
            'password' => '123456',
            'delivery_cost' => '6.20',
        ]);
        $payload = [
            'trade' => 'Company Test',
            'delivery_cost' => '4.20',
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/company/{$company->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyCompany()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $company = Company::create([
            'name' => 'Company Test ME',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'companytest@email.com',
            'password' => '123456',
            'delivery_cost' => '6.20',
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/company/{$company->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testUpdateProfile()
    {
        $company = factory(Company::class)->create();
        $token = $company->createToken('company', ['company'])->accessToken;
        $payload = [
            'name' => 'company 1',
            'password' => 'blue22',
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', '/api/manager/profile', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    private function getTokenWithPermissionAdmin()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
