<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Pipeline;
use App\Services\DealService;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('deals_count')
                    ->counts('deals')
                    ->label('Deals'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('newDeal')
                    ->label('New Deal')
                    ->icon('heroicon-o-plus')
                    ->form([
                        \Filament\Forms\Components\Select::make('pipeline_id')
                            ->label('Pipeline')
                            ->options(Pipeline::pluck('name', 'id'))
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('value')
                            ->numeric()
                            ->label('Expected Value'),
                    ])
                    ->action(function ($record, array $data) {
                        $pipeline = Pipeline::findOrFail($data['pipeline_id']);

                        app(DealService::class)->createFromClient(
                            $record,
                            $pipeline,
                            ['value' => $data['value'] ?? null]
                        );
                    })
                    ->successNotificationTitle('Deal created from client')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
