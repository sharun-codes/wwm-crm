<?php 
namespace App\Domain\CRM\Rules\Stages;

use App\Domain\CRM\Rules\DealStageRule;
use App\Models\Deal;
use App\Models\PipelineStage;
use App\Exceptions\MissingRequirement;

class NegotiationStageRule implements DealStageRule
{
    public function validate(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->slug === 'negotiation' && $deal->activities()->count() === 0) {
            throw new MissingRequirement(
                'Log at least one activity before entering Negotiation.'
            );
        }
    }
}
