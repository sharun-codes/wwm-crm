<?php

namespace App\Filament\Resources\Deals\Schemas;

use App\Filament\Forms\Deal\BaseDealForm;
use App\Filament\Forms\Deal\SalesDealForm;
use App\Filament\Forms\Deal\RenewalDealForm;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class DealForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make()
            ->schema([
            // Pipeline selector MUST come first
            Select::make('pipeline_id')
                ->relationship('pipeline', 'name')
                ->required()
                ->reactive(),

            // Stage selector depends on pipeline
            Select::make('pipeline_stage_id')
                ->relationship(
                    'stage',
                    'name',
                    fn ($query, callable $get) =>
                        $query->where('pipeline_id', $get('pipeline_id'))
                )
                ->required(),

            Select::make('client_id')
            ->relationship('client', 'name')
            ->disabled()
            ->visible(fn ($record) => filled($record?->client_id)),
        ]),
        // Dynamic fields block
        Section::make()
            ->schema(function (callable $get) {
                $pipelineSlug = optional(
                    \App\Models\Pipeline::find($get('pipeline_id'))
                )->slug;

                return array_merge(
                    BaseDealForm::schema(),

                    match ($pipelineSlug) {
                        'sales' => SalesDealForm::schema(),
                        'renewal' => RenewalDealForm::schema(),
                        default => [],
                    }
                );
            }),

        ]);
    }
}
