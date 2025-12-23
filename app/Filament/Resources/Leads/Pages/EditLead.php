<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Activity;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('logActivity')
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
            DeleteAction::make(),
        ];
    }
}
