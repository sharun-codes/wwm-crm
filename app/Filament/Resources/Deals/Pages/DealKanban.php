<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use Filament\Resources\Pages\Page;

use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Services\DealService;
use App\Exceptions\CrmException;
use Filament\Notifications\Notification;

class DealKanban extends Page
{
    protected static string $resource = DealResource::class;

    protected string $view = 'filament.resources.deals.pages.kanban';

    public ?int $pipelineId = null;

    public function mount(): void
    {
        $this->pipelineId ??= Pipeline::where('slug', 'sales')->value('id');
    }

    public function getStagesProperty()
    {
        return PipelineStage::where('pipeline_id', $this->pipelineId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getDealsByStageProperty()
    {
        return Deal::with('lead', 'client')
            ->where('pipeline_id', $this->pipelineId)
            ->get()
            ->groupBy('pipeline_stage_id');
    }

    public function moveDeal(int $dealId, int $stageId): void
    {
        try {
            $deal = Deal::findOrFail($dealId);
            $stage = PipelineStage::findOrFail($stageId);

            app(DealService::class)->moveToStage($deal, $stage);

            Notification::make()
                ->title('Deal moved')
                ->success()
                ->send();

        } catch (CrmException $e) {
            Notification::make()
                ->title('Move blocked')
                ->body($e->userMessage())
                ->danger()
                ->send();
        }
    }
}
