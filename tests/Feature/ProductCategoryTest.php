<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use App\Customer;
use Tests\TestCase;
use App\ProductCategory;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCategoryTest extends TestCase
{
    public function testCreateProductCategory()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'name' => 'Drinks',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/product/category', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateProductCategory()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $category = ProductCategory::create([
            'name' => 'Foods',
        ]);
        $payload = [
            'name' => 'Drinks',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/product/category/{$category->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyProductCategory()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $category = ProductCategory::create([
            'name' => 'Foods',
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/product/category/{$category->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testProfileCompanyListProductCategory()
    {
        $company = factory(Company::class)->create();
        $token = $company->createToken('company', ['company'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('GET', '/api/manager/product/category');

        $response->assertStatus(200);
    }

    public function testProfileCustomerListProductCategory()
    {
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('GET', '/api/product/category');

        $response->assertStatus(200);
    }

    private function getTokenWithPermissionAdmin()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
