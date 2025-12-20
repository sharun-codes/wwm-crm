<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            'leads.view',
            'leads.create',
            'leads.assign',
            'leads.convert',

            'deals.view',
            'deals.update',
            'deals.move_stage',
            'deals.view_value',

            'clients.view',
            'clients.create',

            'companies.view',
            'companies.create',

            'attachments.upload',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $freelancer = Role::firstOrCreate(['name' => 'freelancer']);

        // Superadmin: everything
        $superAdmin->syncPermissions(Permission::all());

        // Manager: everything
        $manager->syncPermissions($permissions);

        // Staff: operational access
        $staff->syncPermissions([
            'leads.view',
            'leads.create',
            'leads.assign',
            'leads.convert',

            'deals.view',
            'deals.update',
            'deals.move_stage',

            'clients.view',
            'clients.create',

            'attachments.upload',
        ]);

        // Freelancer: limited intake
        $freelancer->syncPermissions([
            'leads.view',
            'leads.create',
        ]);
    }
}
