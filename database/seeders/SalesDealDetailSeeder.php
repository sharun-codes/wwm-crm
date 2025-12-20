<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Deal;
use App\Models\SalesDealDetail;

class SalesDealDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Deal::all() as $deal) {
            SalesDealDetail::create([
                'deal_id' => $deal->id,
                'campaign_type' => 'branding',
                'platform' => 'instagram',
                'duration_days' => 30,
                'expected_reach' => rand(10000, 100000),
            ]);
        }
    }
}
