<?php

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

use App\Services\LeadService;
use App\Models\Pipeline;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('source')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                Action::make('qualify')->button()
                    ->label('Qualify')->icon(Heroicon::Check)->color('warning')
                    ->visible(fn ($record) => $record->status !== 'qualified')
                    ->requiresConfirmation()
                    ->modalHeading('Qualify Lead')
                    ->modalDescription('Are you sure this lead is qualified?')
                    ->modalSubmitActionLabel('Yes, Confirm')
                    ->action(function ($record) {
                        $pipeline = Pipeline::where('slug', 'sales')->firstOrFail();
                        app(LeadService::class)->qualify($record, $pipeline);
                        \Log::debug("success");
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
