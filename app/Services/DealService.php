<?php 
namespace App\Services;

use App\Models\Deal;
use App\Models\PipelineStage;
use App\Events\DealWon;
use App\Events\DealLost;

class DealService
{
    public function moveToStage(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->pipeline_id !== $deal->pipeline_id) {
            throw new \DomainException('Stage does not belong to this pipeline.');
        }

        if ($stage->is_won && empty($deal->value)) {
            throw new \DomainException('Deal value required before marking as won.');
        }

        $deal->update([
            'pipeline_stage_id' => $stage->id,
        ]);

        if ($stage->is_won) {
            event(new DealWon($deal));
        }

        if ($stage->is_lost) {
            event(new DealLost($deal));
        }
    }
}
