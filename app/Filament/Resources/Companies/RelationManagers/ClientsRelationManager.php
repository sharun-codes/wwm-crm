<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClientsRelationManager extends RelationManager
{
    protected static string $relationship = 'clients';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->email(),

            Forms\Components\TextInput::make('phone'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\TextColumn::make('created_at')->date(),
        ])->headerActions([
                CreateAction::make(),
        ])->actions([
                EditAction::make(),
        ]);
    }
}
