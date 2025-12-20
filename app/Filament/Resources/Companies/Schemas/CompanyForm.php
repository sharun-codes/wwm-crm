<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('industry')
                    ->maxLength(255),

                TextInput::make('website')
                    ->url()
                    ->maxLength(255),

                TextInput::make('gst')
                    ->label('GST / Tax ID')
                    ->maxLength(50),

                Textarea::make('billing_address')
                    ->rows(3),

                Toggle::make('is_active')
                    ->default(true),
                    ]);
    }
}
