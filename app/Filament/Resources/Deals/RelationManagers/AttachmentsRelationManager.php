<?php

namespace App\Filament\Resources\Deals\RelationManagers;

use App\Filament\Resources\Deals\DealResource; 
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $recordTitleAttribute = 'path';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\FileUpload::make('path')
                ->label('File')
                ->directory('deals/attachments')
                ->preserveFilenames()
                ->required(),

            Forms\Components\Select::make('type')
                ->options([
                    'proposal' => 'Proposal',
                    'invoice' => 'Invoice',
                    'contract' => 'Contract',
                ])
                ->default('proposal')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('path')->label('File'),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
