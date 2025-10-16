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
            // Dashboard
            'view.dashboard',

            // Units
            'view.unit',
            'create.unit',
            'update.unit',
            'delete.unit',

            // Components
            'view.component',
            'create.component',
            'update.component',
            'delete.component',

            // Peripherals
            'view.peripheral',
            'create.peripheral',
            'update.peripheral',
            'delete.peripheral',

            // Laboratories
            'view.laboratories',
            'create.laboratories',
            'update.laboratories',
            'delete.laboratories',

            // Maintenance
            'view.maintenance',
            'create.maintenance',
            'update.maintenance',
            'delete.maintenance',

            // Reports
            'view.reports',
            'export.reports',

            // Users & Roles
            'manage.users',
            'manage.roles',


            // QR Generator
            'view.qr',
            'create.qr',
            'delete.qr',

            // Activity Logs
            'view.activitylogs',
            'delete.activitylogs',
        ];

        // create permissions if not exists
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // roles
        $chairmanRole = Role::firstOrCreate(['name' => 'chairman']);
        $labInchargeRole = Role::firstOrCreate(['name' => 'lab_incharge']);
        $labTechnicianRole = Role::firstOrCreate(['name' => 'lab_technician']);

        $permissions = Permission::get();
        $chairmanRole->syncPermissions($permissions); // chairman gets everything

        // lab in-charge role
        $labInchargeRole->syncPermissions([
            'view.dashboard',

            'view.unit',
            'create.unit',
            'update.unit',
            'view.component',
            'create.component',
            'update.component',
            'view.peripheral',
            'create.peripheral',
            'update.peripheral',

            'view.laboratories',
            'create.laboratories',
            'update.laboratories',

            'view.maintenance',
            'create.maintenance',
            'update.maintenance',

            'view.reports',
            'export.reports',

            'view.qr',
            'create.qr',

            'view.activitylogs',
        ]);

        // lab technician role
        $labTechnicianRole->syncPermissions([
            'view.dashboard',

            'view.unit',
            'update.unit',
            'view.component',
            'update.component',
            'view.peripheral',
            'update.peripheral',

            'view.laboratories',

            'view.maintenance',
            'create.maintenance',
            'update.maintenance',

            'view.reports',

            'view.qr',

            'view.activitylogs',
        ]);

        // system admin (default chairman)
        $admin = User::firstOrCreate(
            ['email' => 'admin@itlab.edu'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'date_of_birth' => '1990-01-01'
            ]
        );

        $admin->assignRole($chairmanRole);

        $admin->securityAnswers()->firstOrCreate(
            ['question' => 'What is your favorite color?'],
            ['answer' => 'Chairman-Blue']
        );


    }
}
