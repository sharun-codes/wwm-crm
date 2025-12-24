<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\Companies\RelationManagers\ClientsRelationManager;
use App\Filament\Resources\Companies\RelationManagers\DealsRelationManager;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Companies\Tables\CompaniesTable;
use App\Models\Company;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    protected static ?string $recordTitleAttribute = 'company';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('companies.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('companies.view');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('companies.create');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->can('companies.update');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->can('companies.delete');
    }

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ClientsRelationManager::class,
            DealsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
