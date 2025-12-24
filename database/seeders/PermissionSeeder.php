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
            // Leads
            'leads.view',
            'leads.create',
            'leads.update',
            'leads.assign', //assign = assign lead to user
            'leads.delete',
            'leads.convert', //convert = qualify -> deal

            // Deals
            'deals.view',
            'deals.create',
            'deals.update',
            'deals.delete',
            'deals.move_stage', //move_stage = Kanban drag & drop
            'deals.view_value', //view_value = hide revenue for some roles

            // Pipelines
            'pipelines.view',
            'pipelines.manage', //Manage = create/update stages, order, rules

            // Clients
            'clients.view',
            'clients.create',
            'clients.update',
            'clients.delete',

            // Companies
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.delete',

            // Activities
            'activities.view',
            'activities.create',
            'activities.delete',

             // Attachments
            'attachments.view',
            'attachments.upload',
            'attachments.delete',


            // Dashboard & Reports
            'dashboard.view',
            'reports.view',
            'reports.export',

             // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.assign_roles',
            'users.reset_password',

            // System
            'audit.view',
            'settings.manage',
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
        $manager->syncPermissions([
            'dashboard.view',
            'reports.view',
            'reports.export',

            'leads.view',
            'leads.create',
            'leads.update',
            'leads.assign',
            'leads.convert',

            'deals.view',
            'deals.create',
            'deals.update',
            'deals.move_stage',
            'deals.view_value',

            'clients.view',
            'clients.create',
            'clients.update',

            'companies.view',
            'companies.create',
            'companies.update',

            'activities.view',
            'activities.create',

            'attachments.view',
            'attachments.upload',

            'pipelines.view',
        ]);

        // Staff: operational access
        $staff->syncPermissions([
            'dashboard.view',

            'leads.view',
            'leads.create',
            'leads.update',

            'deals.view',
            'deals.create',
            'deals.update',
            'deals.move_stage',

            'clients.view',
            'clients.create',

            'companies.view',

            'activities.view',
            'activities.create',

            'attachments.view',
            'attachments.upload',
        ]);

        // Freelancer: limited intake
        $freelancer->syncPermissions([
            'dashboard.view',

            'leads.view',
            'leads.create',
            
            'activities.view',
            'activities.create',
        ]);
    }
}
