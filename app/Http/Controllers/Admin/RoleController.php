<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
{
    $this->middleware(['role_or_permission:admin|permissions.manage']);
}

    public function index()
    {
        $roles = Role::query()->withCount('permissions')->orderBy('name')->paginate(20);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:100','unique:roles,name'],
            'permissions'  => ['nullable','array'],
            'permissions.*'=> ['integer','exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web', 
        ]);

        if (!empty($data['permissions'])) {
            $perms = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($perms);
        }

        return redirect()->route('admin.roles.index')->with('ok', 'Role created');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions()->pluck('id')->all();

        return view('admin.roles.edit', compact('role','permissions','rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'         => ['required','string','max:100',"unique:roles,name,{$role->id}"],
            'permissions'  => ['nullable','array'],
            'permissions.*'=> ['integer','exists:permissions,id'],
        ]);

        $role->update(['name' => $data['name']]);

        $perms = !empty($data['permissions'])
            ? Permission::whereIn('id', $data['permissions'])->get()
            : collect();

        $role->syncPermissions($perms);

        return redirect()->route('admin.roles.index')->with('ok', 'Role updated');
    }

    public function destroy(Role $role)
    {
        
        $role->delete();
        return back()->with('ok', 'Role deleted');
    }
}
