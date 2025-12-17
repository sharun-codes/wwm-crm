<?php 
namespace App\Filament\Forms\Deal;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class RenewalDealForm
{
    public static function schema(): array
    {
        return [
            DatePicker::make('renewal.previous_expiry_date')
                ->required(),

            TextInput::make('renewal.renewal_period_months')
                ->numeric()
                ->required(),

            TextInput::make('renewal.churn_risk')
                ->numeric()
                ->minValue(0)
                ->maxValue(100),
        ];
    }
}
