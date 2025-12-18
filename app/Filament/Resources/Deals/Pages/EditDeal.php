<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\DealService;

class EditDeal extends EditRecord
{
    protected static string $resource = DealResource::class;
    protected ?array $pipelinePayload = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pipelinePayload = $data['sales'] ?? $data['renewal'] ?? null;

        unset($data['sales'], $data['renewal']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->pipelinePayload) {
            app(DealService::class)
                ->updateDetails($this->record, $this->pipelinePayload);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->salesDetails) {
            $data['sales'] = $this->record->salesDetails->toArray();
        }

        if ($this->record->renewalDetails) {
            $data['renewal'] = $this->record->renewalDetails->toArray();
        }

        return $data;
    }

}
