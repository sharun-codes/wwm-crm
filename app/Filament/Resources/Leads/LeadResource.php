<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\CreateLead;
use App\Filament\Resources\Leads\Pages\EditLead;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Filament\Resources\Leads\Schemas\LeadForm;
use App\Filament\Resources\Leads\Tables\LeadsTable;
use App\Filament\Resources\Leads\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Leads\RelationManagers\ContactsRelationManager;
use App\Models\Lead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChevronDoubleRight;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('leads.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('leads.view');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('leads.create');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->can('leads.update');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->can('leads.delete');
    }

    public static function form(Schema $schema): Schema
    {
        return LeadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
