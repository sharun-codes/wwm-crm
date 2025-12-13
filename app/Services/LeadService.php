<?php
namespace App\Services;

use App\Models\Lead;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function qualify(Lead $lead, Pipeline $pipeline): Deal
    {
        if ($lead->status === 'qualified') {
            throw new \DomainException('Lead already qualified.');
        }

        return DB::transaction(function () use ($lead, $pipeline) {
            $firstStage = PipelineStage::where('pipeline_id', $pipeline->id)
                ->orderBy('sort_order')
                ->firstOrFail();

            $deal = Deal::create([
                'lead_id' => $lead->id,
                'pipeline_id' => $pipeline->id,
                'pipeline_stage_id' => $firstStage->id,
                'owner_id' => $lead->assigned_to,
            ]);

            $lead->update(['status' => 'qualified']);

            return $deal;
        });
    }

    public function disqualify(Lead $lead, string $reason = null): void
    {
        $lead->update([
            'status' => 'disqualified',
            'notes' => $reason,
        ]);
    }
}
