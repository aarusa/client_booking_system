<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleUser = Role::create(['name' => 'User']);

        Permission::create(['name' => 'view dashboard']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'manage permission']);
        

        // Give all permissions to Super Admin
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Admin and User permissions as before
        $roleAdmin->givePermissionTo(['view dashboard', 'view users', 'add user', 'edit user', 'delete user', 'view roles', 'add role', 'edit role', 'delete role', 'view permissions', 'manage permission']);
        $roleUser->givePermissionTo('view dashboard', 'view users');
    }
}
