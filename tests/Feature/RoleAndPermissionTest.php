<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionTest extends TestCase
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
            'name' => 'manager',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/role', $payload);

        $response->assertStatus(403)
            ->assertExactJson([
                'message' => trans('common.user_without_permission'),
            ]);
    }

    public function testCreateRole()
    {
        $token = $this->getTokenWithPermissions();
        $payload = [
            'name' => 'manager',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/role', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdateRole()
    {
        $token = $this->getTokenWithPermissions();
        $role = Role::findByName('admin', 'admin');
        $payload = [
            'name' => 'manager',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/role/{$role->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyRole()
    {
        $token = $this->getTokenWithPermissions();
        $role = Role::create(['name' => 'manager', 'guard_name' => 'admin']);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/role/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    public function testSyncPermissionsToRole()
    {
        $token = $this->getTokenWithPermissions();
        $role = Role::findByName('admin', 'admin');

        $payload = [
            'permissions' => [
                (Permission::create(['name' => 'create test', 'guard_name' => 'admin']))->id,
                (Permission::create(['name' => 'edit test', 'guard_name' => 'admin']))->id,
            ],
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', "/api/admin/role/{$role->id}/permissions", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.save_success'),
            ]);
    }

    /**
     *  Teste de middleware que validade se o usuário tem função admin.
     *
     * @return void
     */
    public function testValidationMiddlewarePermission()
    {
        $user = factory(User::class)->create();
        $token = $user->createToken('admin', ['admin'])->accessToken;

        $payload = [
            'name' => 'manager',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/permission', $payload);

        $response->assertStatus(403)
            ->assertExactJson([
                'message' => trans('common.user_without_permission'),
            ]);
    }

    public function testCreatePermission()
    {
        $token = $this->getTokenWithPermissions();
        $payload = [
            'name' => 'edit test',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('POST', '/api/admin/permission', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => trans('common.created_success'),
            ]);
    }

    public function testUpdatePermission()
    {
        $token = $this->getTokenWithPermissions();
        $permission = Permission::create(['name' => 'edit test', 'guard_name' => 'admin']);
        $payload = [
            'name' => 'edit person',
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('PUT', "/api/admin/permission/{$permission->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.updated_success'),
            ]);
    }

    public function testDestroyPermission()
    {
        $token = $this->getTokenWithPermissions();
        $permission = Permission::create(['name' => 'edit test', 'guard_name' => 'admin']);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => "Bearer {$token}",
        ])->json('DELETE', "/api/admin/permission/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => trans('common.deleted_success'),
            ]);
    }

    private function getTokenWithPermissions()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::findByName('admin', 'admin'));

        return $user->createToken('admin', ['admin'])->accessToken;
    }
}
