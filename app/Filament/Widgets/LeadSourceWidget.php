<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\Widget;

class LeadSourceWidget extends Widget
{
    protected string $view = 'filament.widgets.lead-source-widget';

    public function getViewData(): array
    {
        return [
            'sources' => Lead::selectRaw('source, COUNT(*) as total')
                ->groupBy('source')
                ->get(),
        ];
    }
}
