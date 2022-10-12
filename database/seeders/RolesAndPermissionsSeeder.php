<?php

namespace Database\Seeders;

use App\Models\User;
use CreatePermissionTables;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Misc
        $miscPermission = Permission::create(['name' => 'N/A']);

        // User Model
        $userPermission1 = Permission::create(['name' => 'create: user']);
        $userPermission2 = Permission::create(['name' => 'read: user']);
        $userPermission3 = Permission::create(['name' => 'update: user']);
        $userPermission4 = Permission::create(['name' => 'delete: user']);

        // Role Model
        $rolePermission1 = Permission::create(['name' => 'create: role']);
        $rolePermission2 = Permission::create(['name' => 'read: role']);
        $rolePermission3 = Permission::create(['name' => 'update: role']);
        $rolePermission4 = Permission::create(['name' => 'delete: role']);

        // Permission Model
        $permission1 = Permission::create(['name' => 'create: permission']);
        $permission2 = Permission::create(['name' => 'read: permission']);
        $permission3 = Permission::create(['name' => 'update: permission']);
        $permission4 = Permission::create(['name' => 'delete: permission']);

        // ADMINS
        $adminPermission1 = Permission::create(['name' => 'read: admin']);
        $adminPermission2 = Permission::create(['name' => 'update: admin']);

        // Create Roles
        $userRole = Role::create(['name' => 'user'])->syncPermissions([
            $miscPermission,
        ]);

        // Create SuperAdmin Roles
        $superAdminRole = Role::create(['name' => 'super-admin'])->syncPermissions([
            $userPermission1, $userPermission2, $userPermission3, $userPermission4, $rolePermission1, $rolePermission2, $rolePermission3, $rolePermission4,
            $permission1, $permission2, $permission3, $permission4, $adminPermission1, $adminPermission2
        ]);

        // Create Admin Roles
        $adminRole = Role::create(['name' => 'admin'])->syncPermissions([
            $userPermission1, $userPermission2, $userPermission3, $userPermission4, $rolePermission1, $rolePermission2, $rolePermission3, $rolePermission4,
            $permission1, $permission2, $permission3, $permission4, $adminPermission1, $adminPermission2
        ]);

        // Create Accountant Permissions
        $accountantRole = Role::create(['name' => 'accountant'])->syncPermissions([
            $userPermission2,
            $rolePermission2,
            $permission2,
            $adminPermission1
        ]);

        // Create Role Student
        $studentRole = Role::create(['name' => 'student'])->syncPermissions([
            $adminPermission1
        ]);

        // Create Users
        User::create([
            'name' => 'super admin',
            'is_admin' => 1,
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ])->assignRole($superAdminRole);

        User::create([
            'name' => 'admin',
            'is_admin' => 1,
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ])->assignRole($adminRole);

        User::create([
            'name' => 'accountant',
            'is_admin' => 1,
            'email' => 'accountant@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ])->assignRole($accountantRole);

        User::create([
            'name' => 'student',
            'is_admin' => 1,
            'email' => 'student@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ])->assignRole($studentRole);

        for ($i=1; $i < 50; $i++) {
            User::create([
                'name' => 'Test '.$i,
                'is_admin' => 0,
                'email' => 'test'.$i.'@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10)
            ])->assignRole($userRole);
        }
    }
}
