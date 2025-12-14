<?php 
namespace App\Services;

use App\Domain\CRM\Rules\SalesPipelineRules;
use App\Exceptions\InvalidStageTransition;
use App\Exceptions\DealValueRequired;

use App\Models\Deal;
use App\Models\PipelineStage;
use App\Events\DealWon;
use App\Events\DealLost;

class DealService
{
    protected function rulesForPipeline(string $slug): array
    {
        return match ($slug) {
            'sales' => [new SalesPipelineRules()],
            default => [],
        };
    }

    public function moveToStage(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->pipeline_id !== $deal->pipeline_id) {
            throw new InvalidStageTransition();
        }

        foreach ($this->rulesForPipeline($deal->pipeline->slug) as $rule) {
            $rule->validate($deal, $stage);
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
