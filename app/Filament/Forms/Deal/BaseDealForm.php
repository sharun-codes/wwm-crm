<?php
namespace App\Filament\Forms\Deal;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class BaseDealForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('value')
                ->numeric()
                ->label('Deal Value'),

            DatePicker::make('expected_close_date'),
        ];
    }
}
