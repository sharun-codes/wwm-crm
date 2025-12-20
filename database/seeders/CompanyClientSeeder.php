<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Client;

class CompanyClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            'ABC Jewellery',
            'BlueSky Advertising',
            'GreenMart Superstores',
            'Nova Electronics',
        ];

        foreach ($companies as $name) {
            $company = Company::create([
                'name' => $name,
                'industry' => 'Retail',
                'is_active' => true,
            ]);

            Client::create([
                'name' => 'Owner - ' . $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@example.com',
                'phone' => '9' . rand(100000000, 999999999),
                'company_id' => $company->id,
            ]);

            Client::create([
                'name' => 'Manager - ' . $name,
                'email' => 'manager_' . strtolower(str_replace(' ', '', $name)) . '@example.com',
                'phone' => '8' . rand(100000000, 999999999),
                'company_id' => $company->id,
            ]);
        }
    }
}
