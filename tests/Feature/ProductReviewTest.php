<?php

namespace Tests\Feature;

use App\Product;
use App\Customer;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    public function testCreateReviewProduct()
    {
        $product = factory(Product::class)->create();
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
           'review' => 'review test',
           'rate' => 4.3,
           'product_uuid' => $product->uuid,
       ];

        $response = $this->withHeaders([
           'Content-Type' => 'application/json',
           'X-Requested-With' => 'XMLHttpRequest',
           'Authorization' => "Bearer {$token}",
       ])->json('POST', '/api/review/product', $payload);

        $response->assertStatus(201)
           ->assertJson([
               'message' => trans('common.save_success'),
           ]);
    }

    public function testUpdateReviewCompany()
    {
        $product = factory(Product::class)->create();
        $customer = factory(Customer::class)->create();
        $review = $customer->reviewProducts()->create([
            'review' => 'review test',
            'rate' => 4.2,
            'product_uuid' => $product->uuid,
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'review' => 'review test 1',
            'rate' => 3.5,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/review/product/{$review->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyReviewProduct()
    {
        $product = factory(Product::class)->create();
        $customer = factory(Customer::class)->create();
        $review = $customer->reviewProducts()->create([
            'review' => 'review test',
            'rate' => 4.2,
            'product_uuid' => $product->uuid,
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/review/product/{$review->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }
}
