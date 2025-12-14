<?php
namespace App\Domain\CRM\Rules;

use App\Models\Deal;
use App\Models\PipelineStage;

interface DealStageRule
{
    public function validate(Deal $deal, PipelineStage $stage): void;
}
