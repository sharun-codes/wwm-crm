<?php
namespace App\Domain\CRM\Rules;

use App\Models\Deal;
use App\Models\PipelineStage;
use App\Exceptions\DealValueRequired;

class SalesPipelineRules implements DealStageRule
{
    public function validate(Deal $deal, PipelineStage $stage): void
    {
        if ($stage->is_won && empty($deal->value)) {
            throw new DealValueRequired();
        }
    }
}
