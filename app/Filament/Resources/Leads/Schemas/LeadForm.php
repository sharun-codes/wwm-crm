<?php

namespace App\Filament\Resources\Leads\Schemas;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->email(),
                TextInput::make('phone'),
                Select::make('source')
                    ->options([
                        'website' => 'Website',
                        'freelancer' => 'Freelancer',
                        'agency' => 'Agency',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'disqualified' => 'Disqualified',
                    ])
                    ->default('new')
                    ->disabled(fn ($record) => $record?->status === 'qualified'),
                Textarea::make('notes'),
            ]);
    }
}
