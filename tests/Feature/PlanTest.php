<?php

namespace Tests\Feature;

use App\Plan;
use App\User;
use App\Company;
use Tests\TestCase;
use MercadoPago\SDK;
use Spatie\Permission\Models\Role;

class PlanTest extends TestCase
{
    public function testCreatePlan()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'description' => 'Básico',
            'slug' => 'basico',
            'price' => 20.99,
            'rate' => 5.60,
            'commission' => 2.00,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/plan', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdatePlan()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $plan = Plan::create([
            'description' => 'Básico',
            'slug' => 'basico',
            'price' => 20.99,
            'rate' => 5.60,
            'commission' => 2.00,
        ]);
        Role::create(['name' => $plan->slug, 'guard_name' => 'company']);
        $payload = [
            'description' => 'Básico I',
            'slug' => 'basico_1',
            'price' => 10.00,
            'rate' => 3.20,
            'commission' => 2.00,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/plan/{$plan->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyPlan()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $plan = Plan::create([
            'description' => 'Básico',
            'slug' => 'basico',
            'price' => 20.99,
            'rate' => 5.60,
            'commission' => 2.00,
        ]);
        Role::create(['name' => $plan->slug, 'guard_name' => 'company']);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/plan/{$plan->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testBuyPlan()
    {
        $company = factory(Company::class)->create([
            'email' => env('MP_TEST_CUSTOMER_USER'),
            'cpf_cnpj' => '11111111111',
        ]);
        $token = $company->createToken('company', ['company'])->accessToken;
        $plan = Plan::create([
            'description' => 'Básico',
            'slug' => 'basico',
            'price' => 20.99,
            'rate' => 5.60,
            'commission' => 2.00,
        ]);
        $payload = [
            'token' => $this->getTokenCard('approved'),
            'paymentMethodId' => 'visa',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', "/api/manager/plan/{$plan->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.plan_signed_success'),
            ]);
    }

    private function getTokenCard($status)
    {
        SDK::setAccessToken(env('MP_ACCESS_TOKEN'));

        $cards_name_for_status = [
            'approved' => 'APRO',
            'in_process' => 'CONT',
            'call_for_auth' => 'CALL',
            'not_founds' => 'FUND',
            'expirated' => 'EXPI',
            'form_error' => 'FORM',
            'general_error' => 'OTHE',
        ];
        $payload = [
            'json_data' => [
                'card_number' => '4235647728025682',
                'security_code' => '123',
                'expiration_month' => 11,
                'expiration_year' => 2025,
                'cardholder' => [
                    'name' => $cards_name_for_status[$status],
                    'identification' => [
                        'type' => 'CPF',
                        'number' => '11111111111',
                    ],
                ],
            ],
        ];

        $response = SDK::post('/v1/card_tokens', $payload);

        return $response['body']['id'];
    }

    private function getTokenWithPermissionAdmin()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
