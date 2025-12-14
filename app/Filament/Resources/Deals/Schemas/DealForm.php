<?php

namespace App\Filament\Resources\Deals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class DealForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')->numeric(),
                DatePicker::make('expected_close_date'),
            ]);
    }
}
