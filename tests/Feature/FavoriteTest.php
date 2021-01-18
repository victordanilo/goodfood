<?php

namespace Tests\Feature;

use App\Company;
use App\Product;
use App\Customer;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    public function testAddCompanyToFavorites()
    {
        $company = factory(Company::class)->create();
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'company_uuid' => $company->uuid,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/favorite/company', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.save_success'),
            ]);
    }

    public function testRemoveCompanyFromFavorites()
    {
        $company = factory(Company::class)->create();
        $customer = factory(Customer::class)->create();
        $customer->favoriteCompanies()->create(['company_id' => $company->id]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/favorite/company/{$company->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.removed_success'),
            ]);
    }

    public function testAddProductToFavorites()
    {
        $product = factory(Product::class)->create();
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
          'product_uuid' => $product->uuid,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/favorite/product', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.save_success'),
            ]);
    }

    public function testRemoveProductFromFavorites()
    {
        $product = factory(Product::class)->create();
        $customer = factory(Customer::class)->create();
        $customer->favoriteProducts()->create(['product_uuid' => $product->uuid]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/favorite/product/{$product->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.removed_success'),
            ]);
    }
}
