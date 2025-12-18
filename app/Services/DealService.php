<?php 
namespace App\Services;

use App\Domain\CRM\Rules\Stages\ProposalStageRule;
use App\Domain\CRM\Rules\Stages\NegotiationStageRule;
use App\Domain\CRM\Rules\Stages\WonStageRule;
use App\Exceptions\InvalidStageTransition;
use App\Exceptions\DealValueRequired;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Events\DealWon;
use App\Events\DealLost;

class DealService
{
    protected function rulesForPipeline(string $slug): array
    {
        return match ($slug) {
            'sales' => [
                new ProposalStageRule(),
                new NegotiationStageRule(),
                new WonStageRule(),
            ],
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

    public function updateDetails(Deal $deal, array $data): void
    {
        match ($deal->pipeline->slug) {
            'sales' => $deal->salesDetails()
                ->updateOrCreate([], $data),

            'renewal' => $deal->renewalDetails()
                ->updateOrCreate([], $data),

            default => null,
        };
    }

    public function createFromClient(
        Client $client,
        Pipeline $pipeline,
        array $dealData = []
    ): Deal {
        $firstStage = PipelineStage::where('pipeline_id', $pipeline->id)
            ->orderBy('sort_order')
            ->firstOrFail();
    
        return Deal::create([
            'client_id' => $client->id,
            'pipeline_id' => $pipeline->id,
            'pipeline_stage_id' => $firstStage->id,
            'owner_id' => auth()->id(),
            ...$dealData,
        ]);
    }

}
