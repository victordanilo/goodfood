<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Guard Admin
        Permission::create(['name' => 'user.read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'user.create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'user.update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'user.delete', 'guard_name' => 'admin']);
        Permission::create(['name' => 'user.set_role', 'guard_name' => 'admin']);

        Permission::create(['name' => 'company.read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'company.create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'company.update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'company.delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'customer.read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer.create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer.update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'customer.delete', 'guard_name' => 'admin']);

        Permission::create(['name' => 'product_category.read', 'guard_name' => 'admin']);
        Permission::create(['name' => 'product_category.create', 'guard_name' => 'admin']);
        Permission::create(['name' => 'product_category.update', 'guard_name' => 'admin']);
        Permission::create(['name' => 'product_category.delete', 'guard_name' => 'admin']);

        // Guard Company
        Permission::create(['name' => 'company.read', 'guard_name' => 'company']);
        Permission::create(['name' => 'company.create', 'guard_name' => 'company']);
        Permission::create(['name' => 'company.update', 'guard_name' => 'company']);
        Permission::create(['name' => 'company.delete', 'guard_name' => 'company']);

        // Guard Customer
        Permission::create(['name' => 'customer.read', 'guard_name' => 'customer']);
        Permission::create(['name' => 'customer.create', 'guard_name' => 'customer']);
        Permission::create(['name' => 'customer.update', 'guard_name' => 'customer']);
        Permission::create(['name' => 'customer.delete', 'guard_name' => 'customer']);

        // create roles
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $roleUser = Role::create(['name' => 'user', 'guard_name' => 'admin']);

        // assign created permissions
        $roleAdmin->givePermissionTo(Permission::where(['guard_name' => 'admin'])->get());
    }
}
