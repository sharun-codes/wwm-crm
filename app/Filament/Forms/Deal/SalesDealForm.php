<?php 
namespace App\Filament\Forms\Deal;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class SalesDealForm
{
    public static function schema(): array
    {
        return [
            Select::make('sales.campaign_type')
                ->options([
                    'branding' => 'Branding',
                    'performance' => 'Performance',
                ])
                ->required(),

            Select::make('sales.platform')
                ->options([
                    'instagram' => 'Instagram',
                    'google' => 'Google Ads',
                    'facebook' => 'Facebook',
                ])
                ->required(),

            TextInput::make('sales.duration_days')
                ->numeric(),

            TextInput::make('sales.expected_reach')
                ->numeric(),
        ];
    }
}
