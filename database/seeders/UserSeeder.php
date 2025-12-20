<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::firstOrCreate(
            ['email' => 'manager@crm.test'],
            [
                'name' => 'CRM Manager',
                'password' => bcrypt('password'),
            ]
        );
        $manager->assignRole('manager');

        $staff = User::firstOrCreate(
            ['email' => 'staff@crm.test'],
            [
                'name' => 'CRM Staff',
                'password' => bcrypt('password'),
            ]
        );
        $staff->assignRole('staff');

        $freelancer = User::firstOrCreate(
            ['email' => 'freelancer@crm.test'],
            [
                'name' => 'Freelancer User',
                'password' => bcrypt('password'),
            ]
        );
        $freelancer->assignRole('freelancer');
    }
}
