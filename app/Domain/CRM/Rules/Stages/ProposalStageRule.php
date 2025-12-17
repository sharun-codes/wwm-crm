<?php 
namespace App\Domain\CRM\Rules\Stages;

use App\Domain\CRM\Rules\DealStageRule;
use App\Models\Deal;
use App\Models\PipelineStage;
use App\Exceptions\MissingRequirement;

class ProposalStageRule implements DealStageRule
{
    public function validate(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->slug === 'proposal' && $deal->attachments()->count() === 0) {
            throw new MissingRequirement(
                'Please upload a proposal document before moving to Proposal stage.'
            );
        }
    }
}
