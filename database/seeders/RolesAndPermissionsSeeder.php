<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        
        $permissions = [
            'roles.manage',
            'permissions.manage',
            'users.manage',
            'articles.review',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard]
            );
        }

        
        $admin  = Role::firstOrCreate(['name' => 'admin',  'guard_name' => $guard]);
        $author = Role::firstOrCreate(['name' => 'author', 'guard_name' => $guard]);

       
        $admin->syncPermissions(Permission::whereIn('name', $permissions)->get());

        

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
