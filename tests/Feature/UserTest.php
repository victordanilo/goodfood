<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    /**
     *  Teste de middleware que validade se o usuário tem função admin.
     *
     * @return void
     */
    public function testValidationMiddlewareRole()
    {
        $user = factory(User::class)->create();
        $token = $user->createToken('admin', ['admin'])->accessToken;

        $payload = [
            'name' => 'user test',
            'email' => 'usertest@email.com',
            'password' => '123456',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/user', $payload);

        $response->assertStatus(403)
            ->assertExactJson([
                'message' => trans('common.user_without_permission'),
            ]);
    }

    public function testCreateUser()
    {
        $token = $this->getTokenWithPermissions();
        $payload = [
            'name' => 'user test',
            'email' => 'usertest@email.com',
            'password' => '123456',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/user', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateUser()
    {
        $token = $this->getTokenWithPermissions();
        $user = User::create([
            'name' => 'user test',
            'email' => 'usertest@email.com',
            'password' => '123456',
        ]);
        $payload = [
            'name' => 'user 1',
            'password' => 'blue22',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/user/{$user->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyUser()
    {
        $token = $this->getTokenWithPermissions();
        $user = User::create([
            'name' => 'user test',
            'email' => 'usertest@email.com',
            'password' => '123456',
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/user/{$user->uuid}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testSetRoleForUser()
    {
        $token = $this->getTokenWithPermissions();
        $user = User::create([
            'name' => 'user test',
            'email' => 'usertest@email.com',
            'password' => '123456',
        ]);
        $payload = [
            'role' => (Role::findByName('admin', 'admin'))->id,
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', "/api/admin/user/{$user->uuid}/setrole", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.role_defined_success'),
            ]);
    }

    private function getTokenWithPermissions()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
