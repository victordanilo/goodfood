<?php

namespace Tests\Feature;

use App\User;
use App\Customer;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class CustomerTest extends TestCase
{
    public function testValidationCreateCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'name' => 'Customer Test',
            'cpf_cnpj' => '87379532000160',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/customer', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);
    }

    public function testCreateCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $payload = [
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/customer', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $customer = Customer::create([
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ]);
        $payload = [
            'trade' => 'Customer Test',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/customer/{$customer->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $customer = Customer::create([
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/customer/{$customer->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testCreateAddressFromCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $customer = Customer::create([
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ]);
        $payload = [
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
        ])->json('POST', "/api/admin/customer/{$customer->uuid}/address", $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateAddressFromCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $customer = Customer::create([
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ]);
        $address = $customer->addresses()->create([
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ]);

        $payload = [
            'street' => 'R. Brasil',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/customer/{$customer->uuid}/address/{$address->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyAddressFromCustomer()
    {
        $token = $this->getTokenWithPermissionAdmin();
        $customer = Customer::create([
            'name' => 'Customer Test',
            'cpf_cnpj' => '87.379.532/0001-60',
            'email' => 'customertest@email.com',
            'password' => '123456',
        ]);
        $address = $customer->addresses()->create([
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/customer/{$customer->uuid}/address/{$address->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testUpdateProfile()
    {
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'name' => 'Customer 1',
            'password' => 'blue22',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', '/api/profile', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testCreateAddressFromProfile()
    {
        $customer = factory(Customer::class)->create();
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
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
        ])->json('POST', '/api/profile/address', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateAddressFromProfile()
    {
        $customer = factory(Customer::class)->create();
        $address = $customer->addresses()->create([
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;
        $payload = [
            'street' => 'R. Brasil',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/profile/address/{$address->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyAddressFromProfile()
    {
        $customer = factory(Customer::class)->create();
        $address = $customer->addresses()->create([
            'street' => 'R. 232',
            'n' => '310',
            'district' => 'Leste Universitário',
            'zip_code' => '74605-140',
            'city' => 'Goiânia',
            'uf' => 'GO',
        ]);
        $token = $customer->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/profile/address/{$address->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    private function getTokenWithPermissionAdmin()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
