<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\Client;


class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pipeline = Pipeline::where('slug', 'sales')->first();
        $stage = PipelineStage::where('slug', 'discovery')->first();

        // Client-origin deals (upsell / renewal)
        foreach (Client::take(3)->get() as $client) {
            Deal::create([
                'client_id' => $client->id,
                'company_id' => $client->company_id,
                'pipeline_id' => $pipeline->id,
                'pipeline_stage_id' => $stage->id,
                'value' => rand(50000, 300000),
            ]);
        }
    }
}
