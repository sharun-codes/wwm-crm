<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Lead;
use App\Models\User;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        Lead::insert([
            [
                'name' => 'Sunrise Gold',
                'email' => 'contact@sunrisegold.com',
                'phone' => '9876543210',
                'source' => 'freelancer',
                'status' => 'new',
                'assigned_to' => $user->id,
            ],
            [
                'name' => 'Urban Threads',
                'email' => 'hello@urbanthreads.com',
                'phone' => '9123456780',
                'source' => 'website',
                'status' => 'contacted',
                'assigned_to' => $user->id,
            ],
        ]);
    }
}
