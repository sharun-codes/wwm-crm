<?php 

namespace App\Filament\Resources\Deals\Widgets;

use Filament\Widgets\Widget;
use App\Models\Deal;

class DealContactsWidget extends Widget
{
    protected string $view = 'filament.resources.deals.widgets.deal-contacts';

    public ?Deal $record;

    public function getContactsProperty()
    {
        return $this->record->client
            ? $this->record->client->contacts
            : $this->record->lead?->contacts;
    }
}
