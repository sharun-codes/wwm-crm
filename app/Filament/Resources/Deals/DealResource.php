<?php

namespace App\Filament\Resources\Deals;

use App\Filament\Resources\Deals\Pages\CreateDeal;
use App\Filament\Resources\Deals\Pages\EditDeal;
use App\Filament\Resources\Deals\Pages\ListDeals;
use App\Filament\Resources\Deals\Pages\DealKanban;
use App\Filament\Resources\Deals\Schemas\DealForm;
use App\Filament\Resources\Deals\Tables\DealsTable;
use App\Filament\Resources\Deals\RelationManagers\AttachmentsRelationManager;
use App\Filament\Resources\Deals\RelationManagers\ActivitiesRelationManager;
use App\Models\Deal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HandThumbUp;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('deals.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('deals.view');
    }

    public static function canCreate(): bool
    {
        return false;
        // return Auth::user()->can('deals.create');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->can('deals.update');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->can('deals.delete');
    }

    public static function form(Schema $schema): Schema
    {
        return DealForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DealsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
            AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeals::route('/'),
            'kanban' => DealKanban::route('/kanban'),
            'create' => CreateDeal::route('/create'),
            'edit' => EditDeal::route('/{record}/edit'),
        ];
    }
}
