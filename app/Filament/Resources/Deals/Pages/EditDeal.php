<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use App\Models\Activity;
use Filament\Actions\Action;
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
            Action::make('logActivity')->visible(fn () => auth()->user()->can('activities.create'))
                ->label('Log Activity')
                ->icon('heroicon-o-plus')
                ->modalHeading('Log Activity')
                ->modalSubmitActionLabel('Save Activity')
                ->form([
                    \Filament\Forms\Components\Select::make('type')
                        ->options([
                            'call' => 'Call',
                            'meeting' => 'Meeting',
                            'email' => 'Email',
                            'note' => 'Note',
                        ])
                        ->required(),

                    \Filament\Forms\Components\Textarea::make('message')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    Activity::create([
                        'subject_type' => $record::class,
                        'subject_id' => $record->id,
                        'type' => $data['type'],
                        'message' => $data['message'],
                        'user_id' => auth()->id(),
                    ]);
                })
                ->successNotificationTitle('Activity logged'),
            DeleteAction::make()->visible(fn () => auth()->user()->can('deals.delete')),
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
