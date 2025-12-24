<?php

namespace App\Filament\Resources\Leads\Schemas;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Fieldset;
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
                Fieldset::make('Basic Info')
                    ->schema([
                        TextInput::make('name')->label('Title')->required(),
                        Select::make('source')
                            ->options([
                                'direct' => 'Direct',
                                'email' => 'Email',
                                'phone' => 'Phone',
                                'existing_customer' => 'Existing Customer',
                                'campaign' => 'Campaign',
                                'web' => 'Web',
                                'website' => 'Website',
                                'freelancer' => 'Freelancer',
                                'agency' => 'Agency',
                                'referral' => 'Referral',
                                'other' => 'Other',
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
                            ]),
                    Fieldset::make('Primary Contact')
                        ->schema([
                            TextInput::make('email')->label('Primary Email')->email(),
                            TextInput::make('phone')->label('Primary Phone'),
                        ]),
                    Fieldset::make('Notes')
                        ->schema([ 
                            Textarea::make('notes'),
                        ])
            ]);
    }
}
