<?php

namespace Tests\Feature;

use App\Company;
use App\Customer;
use Tests\TestCase;

class CompanyReviewTest extends TestCase
{
    public function testCreateReviewCompany()
    {
        $company = factory(Company::class)->create();
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'review' => 'review test',
            'rate' => 4.3,
            'company_uuid' => $company->uuid,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/review/company', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.save_success'),
            ]);
    }

    public function testUpdateReviewCompany()
    {
        $company = factory(Company::class)->create();
        $customer = factory(Customer::class)->create();
        $review = $customer->reviewCompanies()->create([
            'review' => 'review test',
            'rate' => 4.3,
            'company_uuid' => $company->uuid,
            'customer_uuid' => $customer->uuid,
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'review' => 'review test 1',
            'rate' => 2.5,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/review/company/{$review->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyReviewCompany()
    {
        $company = factory(Company::class)->create();
        $customer = factory(Customer::class)->create();
        $review = $customer->reviewCompanies()->create([
            'review' => 'review test',
            'rate' => 4.3,
            'company_uuid' => $company->uuid,
            'customer_uuid' => $customer->uuid,
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/review/company/{$review->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }
}
