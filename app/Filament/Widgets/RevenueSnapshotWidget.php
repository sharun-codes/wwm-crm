<?php

namespace App\Filament\Widgets;

use App\Models\Deal;
use Filament\Widgets\Widget;

class RevenueSnapshotWidget extends Widget
{
    protected string $view = 'filament.widgets.revenue-snapshot-widget';

    public function getViewData(): array
    {
        return [
            'total' => Deal::sum('value'),
            'open' => Deal::whereHas('stage', fn ($q) => $q->where('is_won', false))->sum('value'),
            'won' => Deal::whereHas('stage', fn ($q) => $q->where('is_won', true))->sum('value'),
        ];
    }
}
