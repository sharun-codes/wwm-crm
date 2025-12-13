<?php
namespace App\Actions\Deal;

use App\Models\Deal;
use App\Models\PipelineStage;
use App\Services\DealService;

class CloseDeal
{
    public function __construct(
        protected DealService $service
    ) {}

    public function win(Deal $deal, PipelineStage $stage): void
    {
        $this->service->moveToStage($deal, $stage);
    }
}
