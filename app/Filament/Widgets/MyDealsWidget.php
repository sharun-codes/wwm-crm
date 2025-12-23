<?php

namespace App\Filament\Widgets;

use App\Models\Deal;
use Filament\Widgets\Widget;

class MyDealsWidget extends Widget
{
    protected string $view = 'filament.widgets.my-deals-widget';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'deals' => Deal::where('owner_id', auth()->id())
                ->latest()
                ->take(5)
                ->get(),
        ];
    }
}
