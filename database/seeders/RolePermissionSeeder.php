<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view.dashboard',
            'view.equipment',
            'create.equipment',
            'update.equipment',
            'delete.equipment',
            'view.laboratories',
            'create.laboratories',
            'update.laboratories',
            'delete.laboratories',
            'view.maintenance',
            'create.maintenance',
            'update.maintenance',
            'delete.maintenance',
            'view.reports',
            'export.reports',
            'manage.users',
            'manage.roles',
            'assign.laboratory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $chairmanRole = Role::firstOrCreate(['name' => 'chairman']);
        $labInchargeRole = Role::firstOrCreate(['name' => 'lab_incharge']);
        $labTechnicianRole = Role::firstOrCreate(['name' => 'lab_technician']);

        $permissions = Permission::get();
        $chairmanRole->syncPermissions($permissions); // assign all permissions to chairman



        $labInchargeRole->syncPermissions([
            'view.dashboard',
            'view.equipment',
            'create.equipment',
            'update.equipment',
            'view.laboratories',
            'view.maintenance',
            'create.maintenance',
            'update.maintenance',
            'view.reports',
            'export.reports',
            'assign.laboratory',
        ]);

        $labTechnicianRole->syncPermissions([
            'view.dashboard',
            'view.equipment',
            'update.equipment',
            'view.laboratories',
            'view.maintenance',
            'create.maintenance',
            'update.maintenance',
            'view.reports',
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@itlab.edu'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole($chairmanRole);
    }
}
