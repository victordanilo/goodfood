<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use App\Customer;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     *  registro customer.
     *
     * @return void
     */
    public function testSignupCustomer()
    {
        $payload = [
            'name' => 'fake user',
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'password_confirmation' => '1232131',
        ];

        $response = $this->postJson('/api/auth/signup', $payload);

        $response->assertStatus(201)
            ->assertExactJson([
                'message' => trans('auth.signup_success'),
            ]);
    }

    /**
     *  registro company.
     *
     * @return void
     */
    public function testSignupCompany()
    {
        $payload = [
            'name' => 'fake user',
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'password_confirmation' => '1232131',
        ];

        $response = $this->postJson('/api/manager/auth/signup', $payload);

        $response->assertStatus(201)
            ->assertExactJson([
                'message' => trans('auth.signup_success'),
            ]);
    }

    /**
     *  registro admin.
     *
     * @return void
     */
    public function testSignupAdmin()
    {
        $payload = [
            'name' => 'fake user',
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'password_confirmation' => '1232131',
        ];

        $response = $this->postJson('/api/admin/auth/signup', $payload);

        $response->assertStatus(201)
            ->assertExactJson([
                'message' => trans('auth.signup_success'),
            ]);
    }

    /**
     * Test Auth customer with client fake.
     *
     * @return void
     */
    public function testAuthCustomerClientFake()
    {
        $payload = [
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(422)
                    ->assertExactJson([
                        'message' => trans('auth.account_not_exist'),
                    ]);
    }

    /**
     * Test Auth company with client fake.
     *
     * @return void
     */
    public function testAuthCompanyClientFake()
    {
        $payload = [
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/manager/auth/login', $payload);

        $response->assertStatus(422)
            ->assertExactJson([
                'message' => trans('auth.account_not_exist'),
            ]);
    }

    /**
     * Test Auth admin with client fake.
     *
     * @return void
     */
    public function testAuthAdminClientFake()
    {
        $payload = [
            'email' => 'fakeemail@eti.com.br',
            'password' => '1232131',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/admin/auth/login', $payload);

        $response->assertStatus(422)
            ->assertExactJson([
                'message' => trans('auth.account_not_exist'),
            ]);
    }

    /**
     * Test Auth Customer Success.
     *
     * @return void
     */
    public function testAuthCustomerSuccess()
    {
        $client = factory(Customer::class)->create();

        $payload = [
            'email' => $client->email,
            'password' => 'password',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_at'])
            ->assertJson([
                'token_type' => 'Bearer',
            ]);
    }

    /**
     * Test Auth Company Success.
     *
     * @return void
     */
    public function testAuthCompanySuccess()
    {
        $client = factory(Company::class)->create();

        $payload = [
            'email' => $client->email,
            'password' => 'password',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/manager/auth/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_at'])
            ->assertJson([
                'token_type' => 'Bearer',
            ]);
    }

    /**
     * Test Auth Admin Success.
     *
     * @return void
     */
    public function testAuthAdminSuccess()
    {
        $client = factory(User::class)->create();

        $payload = [
            'email' => $client->email,
            'password' => 'password',
            'remember_me' => true,
        ];

        $response = $this->postJson('/api/admin/auth/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_at'])
            ->assertJson([
                'token_type' => 'Bearer',
            ]);
    }

    /**
     * Logout customer.
     *
     * @return void
     */
    public function testLogoutCustomer()
    {
        $user = factory(Customer::class)->create();
        $token = $user->createToken('customer', ['customer'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('GET', '/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('auth.logout_success'),
            ]);
    }

    /**
     * Logout company.
     *
     * @return void
     */
    public function testLogoutCompany()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('GET', '/api/manager/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('auth.logout_success'),
            ]);
    }

    /**
     * Logout admin.
     *
     * @return void
     */
    public function testLogoutAdmin()
    {
        $user = factory(User::class)->create();
        $token = $user->createToken('admin', ['admin'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('GET', '/api/admin/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('auth.logout_success'),
            ]);
    }

    /**
     * Checagem de escopo de atutenticação de cliente.
     */
    public function testScopeAuthCustomer()
    {
        $user = factory(User::class)->create();
        $token = $user->createToken('admin', ['admin'])->accessToken;

        $response = $this->withHeaders([
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                ['Authorization' => "Bearer {$token}"],
        ])->json('GET', '/api/auth/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => trans('auth.not_authenticated'),
            ]);
    }

    /**
     * Checagem de escopo de atutenticação de companinha.
     */
    public function testScopeAuthCompany()
    {
        $user = factory(User::class)->create();
        $token = $user->createToken('company', ['company'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            ['Authorization' => "Bearer {$token}"],
        ])->json('GET', '/api/auth/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => trans('auth.not_authenticated'),
            ]);
    }

    /**
     * Checagem de escopo de atutenticação de admin.
     */
    public function testScopeAuthAdmin()
    {
        $user = factory(Company::class)->create();
        $token = $user->createToken('admin', ['admin'])->accessToken;

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            ['Authorization' => "Bearer {$token}"],
        ])->json('GET', '/api/auth/user');

        $response->assertStatus(401)
            ->assertExactJson([
                'message' => trans('auth.not_authenticated'),
            ]);
    }
}
