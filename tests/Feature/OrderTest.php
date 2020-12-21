<?php

namespace Tests\Feature;

use App\Company;
use App\Customer;
use Tests\TestCase;
use MercadoPago\SDK;
use App\ProductCategory;

class OrderTest extends TestCase
{
    public function testValidationAuthInCreateOrder()
    {
        $token = sha1('test');
        $cart = $this->getCart();
        $payload = [
            'token' => sha1('test'),
            'paymentMethodId' => 'visa',
            'products' => $cart,
            'delivery_address' => 'Av. C-2, 436 - Jardim America, Goiânia - GO, 74265-020',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/order', $payload);

        $response->assertStatus(401)
            ->assertJson([
                'message' => trans('auth.not_authenticated'),
            ]);
    }

    public function testValidationStockCreateOrder()
    {
        $token = $this->getTokenWithPermissionCustomer();
        $cart = $this->getCart();
        $cart[0]['qty'] = 25;
        $payload = [
            'token' => sha1('test'),
            'paymentMethodId' => 'visa',
            'products' => $cart,
            'delivery_address' => 'Av. C-2, 436 - Jardim America, Goiânia - GO, 74265-020',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/order', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => trans('common.product_unavailable', ['product' => 'Energetic drink 473ml']),
            ]);
    }

    public function testCreateOrder()
    {
        $token = $this->getTokenWithPermissionCustomer();
        $cart = $this->getCart();
        $payload = [
            'token' => $this->getTokenCard('approved'),
            'paymentMethodId' => 'visa',
            'products' => $cart,
            'delivery_address' => 'Av. C-2, 436 - Jardim America, Goiânia - GO, 74265-020',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/order', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.order_process_success'),
            ]);
    }

    private function getCart()
    {
        $productCategory = ProductCategory::create(['name' => 'drinks']);
        $company = factory(Company::class)->create();
        $cart = [];

        $company->credential()->create([
            'mp_user_id' => env('MP_TEST_SELLER1_ID'),
            'public_key' => '',
            'access_token' => '',
            'refresh_token' => '',
            'expires_in' => 11111111,
        ]);
        $product = $company->products()->create([
           'description' => 'Energetic drink 473ml',
           'price' => '6.20',
           'stock' => 10,
           'category_uuid' => $productCategory->uuid,
        ]);
        $cart[] = [
          'uuid' => $product->uuid,
          'qty' => 10,
        ];

        $company = factory(Company::class)->create();
        $company->credential()->create([
            'mp_user_id' => env('MP_TEST_SELLER2_ID'),
            'public_key' => '',
            'access_token' => '',
            'refresh_token' => '',
            'expires_in' => 11111111,
        ]);
        $product = $company->products()->create([
           'description' => 'Coca Cola 310ml',
           'price' => '4.20',
           'stock' => 10,
           'category_uuid' => $productCategory->uuid,
        ]);
        $cart[] = [
           'uuid' => $product->uuid,
           'qty' => 10,
       ];

        return $cart;
    }

    private function getTokenCard($status)
    {
        SDK::setAccessToken(env('MP_TEST_ACCESS_TOKEN'));

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

    private function getTokenWithPermissionCustomer()
    {
        $customer = factory(Customer::class)->create([
            'email' => env('MP_TEST_CUSTOMER_USER'),
            'cpf_cnpj' => '11111111111',
        ]);

        return $customer->createToken('customer', ['customer'])->accessToken;
    }
}
