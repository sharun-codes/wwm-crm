<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Pipeline;
use App\Models\PipelineStage;

class PipelineSeeder extends Seeder
{
    public function run(): void
    {
        $sales = Pipeline::firstOrCreate(
            ['slug' => 'sales'],
            ['name' => 'Sales', 'is_active' => true]
        );

        $stages = [
            ['name' => 'Discovery', 'slug' => 'discovery', 'sort_order' => 1],
            ['name' => 'Proposal', 'slug' => 'proposal', 'sort_order' => 2],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'sort_order' => 3],
            ['name' => 'Won', 'slug' => 'won', 'sort_order' => 99, 'is_won' => true],
            ['name' => 'Lost', 'slug' => 'lost', 'sort_order' => 100, 'is_lost' => true],
        ];

        foreach ($stages as $stage) {
            PipelineStage::firstOrCreate(
                ['pipeline_id' => $sales->id, 'slug' => $stage['slug']],
                array_merge($stage, ['pipeline_id' => $sales->id])
            );
        }
    }
}

