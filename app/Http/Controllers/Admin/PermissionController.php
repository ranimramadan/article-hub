<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
{
    $this->middleware(['role_or_permission:admin|permissions.manage']);
}

    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(30);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150','unique:permissions,name'],
        ]);

        Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('ok', 'Permission created');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150',"unique:permissions,name,{$permission->id}"],
        ]);

        $permission->update(['name' => $data['name']]);

        return redirect()->route('admin.permissions.index')->with('ok', 'Validation updated');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('ok', 'Permission deleted');
    }
}
