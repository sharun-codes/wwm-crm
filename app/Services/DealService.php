<?php 
namespace App\Services;

use App\Exceptions\InvalidStageTransition;
use App\Exceptions\DealValueRequired;

use App\Models\Deal;
use App\Models\PipelineStage;
use App\Events\DealWon;
use App\Events\DealLost;

class DealService
{
    public function moveToStage(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->pipeline_id !== $deal->pipeline_id) {
            throw new InvalidStageTransition();
        }

        if ($stage->is_won && empty($deal->value)) {
            throw new DealValueRequired();
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
