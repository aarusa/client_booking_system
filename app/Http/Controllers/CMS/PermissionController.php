<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return view('cms.modules.users.permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Display permissions for a specific role.
     *
     * @param  int  $roleId
     * @return \Illuminate\Http\Response
     */
    public function rolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $rolePermissions = $role->permissions;
        $allPermissions = Permission::all();

        return view('cms.modules.users.permissions.role', compact('role', 'rolePermissions', 'allPermissions'));
    }

    /**
     * Assign permission to role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roleId
     * @param  int  $permissionId
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request, $roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);
        $currentUser = auth()->user();

        // Allow Super Admin users to modify Super Admin role permissions
        // Prevent other users from modifying Super Admin role permissions
        if ($role->name === 'Super Admin' && !$currentUser->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Only Super Admin users can modify Super Admin permissions.');
        }

        $role->givePermissionTo($permission);

        return redirect()->back()->with('success', "Permission assigned to role successfully.");
    }

    /**
     * Revoke permission from role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roleId
     * @param  int  $permissionId
     * @return \Illuminate\Http\Response
     */
    public function revokePermission(Request $request, $roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);
        $currentUser = auth()->user();

        // Allow Super Admin users to modify Super Admin role permissions
        // Prevent other users from modifying Super Admin role permissions
        if ($role->name === 'Super Admin' && !$currentUser->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Only Super Admin users can modify Super Admin permissions.');
        }

        $role->revokePermissionTo($permission);

        return redirect()->back()->with('success', "Permission revoked from role successfully.");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cms.modules.users.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ],[
            'name.required' => 'Please enter the permission name.',
        ]);

        $permission = new Permission();
        $permission->name = $request->name;
        $permission->guard_name = 'web';
        $permission->save();

        // Auto-assign to Super Admin
        $superAdmin = \Spatie\Permission\Models\Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permission);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully and assigned to Super Admin.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        $roles = Role::all();

        return view('cms.modules.users.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('cms.modules.users.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
        ],[
            'name.required' => 'Please enter the permission name.',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Check if the permission is assigned to any role
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')->with('error', 'This permission is assigned to one or more roles and cannot be deleted.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
} 