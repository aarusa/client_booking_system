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
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);

        // Dashboard Widget Permissions
        Permission::firstOrCreate(['name' => 'view dashboard stats']);
        Permission::firstOrCreate(['name' => 'view dashboard appointments']);
        Permission::firstOrCreate(['name' => 'view dashboard business metrics']);
        Permission::firstOrCreate(['name' => 'view dashboard service revenue']);
        Permission::firstOrCreate(['name' => 'view dashboard client activity']);
        Permission::firstOrCreate(['name' => 'view dashboard financial summary']);
        Permission::firstOrCreate(['name' => 'view dashboard upcoming appointments']);
        Permission::firstOrCreate(['name' => 'view dashboard dog breeds']);

        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'add user']);
        Permission::firstOrCreate(['name' => 'edit user']);
        Permission::firstOrCreate(['name' => 'delete user']);

        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'add role']);
        Permission::firstOrCreate(['name' => 'edit role']);
        Permission::firstOrCreate(['name' => 'delete role']);

        Permission::firstOrCreate(['name' => 'view permissions']);
        Permission::firstOrCreate(['name' => 'add permission']);
        Permission::firstOrCreate(['name' => 'edit permission']);
        Permission::firstOrCreate(['name' => 'delete permission']);
        Permission::firstOrCreate(['name' => 'manage permission']);

        // Client permissions (now include dog management)
        Permission::firstOrCreate(['name' => 'view client']);
        Permission::firstOrCreate(['name' => 'add client']);
        Permission::firstOrCreate(['name' => 'edit client']);
        Permission::firstOrCreate(['name' => 'delete client']);

        // Dog Permissions
        Permission::firstOrCreate(['name' => 'view dog']);
        Permission::firstOrCreate(['name' => 'add dog']);
        Permission::firstOrCreate(['name' => 'edit dog']);
        Permission::firstOrCreate(['name' => 'delete dog']);

        // Service Permissions
        Permission::firstOrCreate(['name' => 'view service']);
        Permission::firstOrCreate(['name' => 'add service']);
        Permission::firstOrCreate(['name' => 'edit service']);
        Permission::firstOrCreate(['name' => 'delete service']);

        // Appointment Permissions
        $appointmentPermissions = [
            'appointment-access',
            'appointment-create',
            'appointment-view',
            'appointment-edit',
            'appointment-delete',
        ];
        foreach ($appointmentPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Give all permissions to Super Admin
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Admin permissions - full access to all dashboard widgets
        $roleAdmin->givePermissionTo([
            'view dashboard stats', 'view dashboard appointments', 'view dashboard business metrics', 
            'view dashboard service revenue', 'view dashboard client activity', 'view dashboard financial summary',
            'view dashboard upcoming appointments', 'view dashboard dog breeds',
            'view users', 'add user', 'edit user', 'delete user', 'view roles', 'add role', 'edit role', 'delete role', 
            'view permissions', 'manage permission', 'view client', 'add client', 'edit client', 'delete client', 
            'view dog', 'add dog', 'edit dog', 'delete dog', 'view service', 'add service', 'edit service', 'delete service'
        ]);
        
        // Assign appointment permissions
        $roleSuperAdmin->givePermissionTo($appointmentPermissions);
        $roleAdmin->givePermissionTo([
            'appointment-access',
            'appointment-create',
            'appointment-view',
            'appointment-edit',
            'appointment-delete',
        ]);
    }
}
