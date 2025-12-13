<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'super-admin',
            'manager',
            'staff',
            'freelancer',
        ] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
