<?php

namespace App\Filament\Resources\Deals\Tables;

use Filament\Notifications\Notification;
use App\Exceptions\DealStageBlockedException;
use App\Exceptions\MissingRequirement;

use App\Services\DealService;
use App\Models\PipelineStage;
use App\Models\Activity;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

class DealsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.name')->label('Lead'),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('pipeline.name'),
                TextColumn::make('stage.name')->badge(),
                TextColumn::make('value')->money('INR'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                
                Action::make('moveStage')
                    ->label('Move Stage')
                    ->form([
                        Select::make('stage_id')
                            ->options(fn ($record) =>
                                $record->pipeline->stages
                                    ->pluck('name', 'id')
                            )
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        try {
                        $stage = PipelineStage::findOrFail($data['stage_id']);
                        app(DealService::class)->moveToStage($record, $stage);

                        Notification::make()
                            ->title('Stage updated')
                            ->success()
                            ->send();
                            
                        } catch (DealStageBlockedException $e) {

                            Activity::create([
                                'subject_type' => Deal::class,
                                'subject_id' => $record->id,
                                'user_id' => auth()->id(),
                                'type' => 'stage_blocked',
                                'message' => $e->reason,
                            ]);

                            Notification::make()
                                ->title('Action blocked')
                                ->body($e->reason)
                                ->danger()
                                ->send();

                            match ($e->action) {
                                'add_contact' => redirect()->to(
                                    route('filament.admin.resources.deals.edit', [
                                        'record' => $record->id,
                                        'activeRelationManager' => 'contacts',
                                    ])
                                ),
                    
                                'edit_value' => redirect()->to(
                                    route('filament.admin.resources.deals.edit', $record->id)
                                ),
                    
                                default => null,
                            };
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function openAddContactModalFromList(int $dealId): void
    {
        redirect()->to(
            route('filament.admin.resources.deals.edit', [
                'record' => $dealId,
                'activeRelationManager' => 'contacts',
            ])
        );
    }

    protected function redirectToEditDeal(int $dealId): void
    {
        redirect()->to(
            route('filament.admin.resources.deals.edit', $dealId)
        );
    }
}
