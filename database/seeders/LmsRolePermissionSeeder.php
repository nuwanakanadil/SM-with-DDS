<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LmsRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'manage_students',
            'manage_assessments',
            'manage_results',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $admin->syncPermissions($permissions);
        $staff->syncPermissions([
            'view_dashboard',
            'manage_students',
            'manage_assessments',
            'manage_results',
        ]);
        $student->syncPermissions(['view_dashboard']);
    }
}
