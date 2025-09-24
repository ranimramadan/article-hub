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
        // امسحي كاش الصلاحيات قبل/بعد
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        // الصلاحيات المطلوبة للوحة الأدمن
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

        // الأدوار
        $admin  = Role::firstOrCreate(['name' => 'admin',  'guard_name' => $guard]);
        $author = Role::firstOrCreate(['name' => 'author', 'guard_name' => $guard]);

        // أعطِ كل الصلاحيات لدور الأدمن
        $admin->syncPermissions(Permission::whereIn('name', $permissions)->get());

        // (اختياري) ما في صلاحيات خاصة للـauthor حالياً

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
