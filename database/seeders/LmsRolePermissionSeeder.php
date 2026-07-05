<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class LmsRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(Permissions::cases())
            ->map(fn (Permissions $permission) => $permission->value);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web',
        ]);

        $student = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions($permissions->toArray());

        $staff->syncPermissions([
            Permissions::ViewDashboard->value,
            Permissions::ViewStudents->value,
            Permissions::ManageStudents->value,
            Permissions::ViewAssessments->value,
            Permissions::ManageAssessments->value,
            Permissions::ViewResults->value,
            Permissions::ManageResults->value,
            Permissions::ViewAnalysis->value,
        ]);

        $student->syncPermissions([
            Permissions::ViewDashboard->value,
            Permissions::ViewOwnResults->value,
            Permissions::ViewOwnPlacement->value,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}