<?php 
namespace App\Services;

use App\Domain\CRM\Rules\Stages\ProposalStageRule;
use App\Domain\CRM\Rules\Stages\NegotiationStageRule;
use App\Domain\CRM\Rules\Stages\WonStageRule;
use App\Exceptions\InvalidStageTransition;
use App\Exceptions\DealValueRequired;
use App\Exceptions\DealStageBlockedException;

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

    protected function ensurePrimaryContactExists(Deal $deal): void
    {
        $lead = $deal->lead;

        $hasPrimary = $lead
            ?->contacts()
            ->where('is_primary', true)
            ->exists();

        if (! $hasPrimary) {
            throw new MissingRequirement(
                'A primary contact is required before marking a deal as WON.'
            );
        }
    }

    public function moveToStage(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->pipeline_id !== $deal->pipeline_id) {
            throw new InvalidStageTransition();
        }

        // foreach ($this->rulesForPipeline($deal->pipeline->slug) as $rule) {
        //     $rule->validate($deal, $stage);
        // }

        if ($stage->slug === 'negotiation' && $deal->activities()->count() === 0) {
            throw new DealStageBlockedException(
                reason: 'At least one activity is required before negotiation.',
                action: 'add_activity'
            );
        }

        if ($stage->slug === 'won') {

            if ($deal->value <= 0) {
                throw new DealStageBlockedException(
                    reason: 'Deal value must be greater than zero before marking as WON.',
                    action: 'edit_value'
                );
            }

            $hasPrimaryContact = $deal->lead
            ->contacts()
            ->where('is_primary', true)
            ->exists();

            if (! $hasPrimaryContact) {
                throw new DealStageBlockedException(
                    reason: 'A primary contact is required before marking this deal as WON.',
                    action: 'add_contact'
                );
            }
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
            'company_id' => $client->company_id,
            'pipeline_id' => $pipeline->id,
            'pipeline_stage_id' => $firstStage->id,
            'owner_id' => auth()->id(),
            ...$dealData,
        ]);
    }

}
