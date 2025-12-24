<?php

namespace App\Filament\Widgets;

use App\Models\PipelineStage;
use Filament\Widgets\Widget;

class PipelineOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.pipeline-overview-widget';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->can('deals.view');
    }

    public function getViewData(): array
    {
        return [
            'stages' => PipelineStage::withCount('deals')
                ->orderBy('sort_order')
                ->get(),
        ];
    }
}
