<?php

namespace Tests\Feature;

use App\Company;
use App\Product;
use Tests\TestCase;
use App\ProductCategory;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    public function testValidationCreateProduct()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;
        $payload = [
            'description' => 'Energetic drink',
            'price' => '6.20',
            'category_uuid' => 'b4e6efe1-f83f-421d-884c-983ceec5b162',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/manager/product', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);
    }

    public function testCreateProduct()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;
        $payload = [
            'description' => 'Energetic drink',
            'price' => '6.20',
            'category_uuid' => (ProductCategory::create(['name' => 'drinks']))->uuid,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/manager/product', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateProduct()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;
        $product = Product::create([
            'description' => 'Energetic drink',
            'price' => '6.20',
            'company_uuid' => $user->uuid,
            'category_uuid' => (ProductCategory::create(['name' => 'drinks']))->uuid,
        ]);
        $payload = [
            'price' => '7.20',
            'stock' => 2,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/manager/product/{$product->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyProduct()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;
        $product = Product::create([
            'description' => 'Energetic drink',
            'price' => '6.20',
            'company_uuid' => $user->uuid,
            'category_uuid' => (ProductCategory::create(['name' => 'drinks']))->uuid,
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/manager/product/{$product->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }
}
