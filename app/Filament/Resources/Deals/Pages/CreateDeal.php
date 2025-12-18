<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\DealService;

class CreateDeal extends CreateRecord
{
    protected static string $resource = DealResource::class;
    protected ?array $pipelinePayload = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract pipeline-specific payload
        $this->pipelinePayload = $data['sales'] ?? $data['renewal'] ?? null;

        // Remove it from Deal payload
        unset($data['sales'], $data['renewal']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->pipelinePayload) {
            app(DealService::class)
                ->updateDetails($this->record, $this->pipelinePayload);
        }
    }

}
