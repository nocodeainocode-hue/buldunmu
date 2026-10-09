<?php

namespace App\Filament\Resources\AdCampaigns;

use App\Filament\Resources\AdCampaigns\Pages\CreateAdCampaign;
use App\Filament\Resources\AdCampaigns\Pages\EditAdCampaign;
use App\Filament\Resources\AdCampaigns\Pages\ListAdCampaigns;
use App\Filament\Resources\AdCampaigns\Schemas\AdCampaignForm;
use App\Filament\Resources\AdCampaigns\Tables\AdCampaignsTable;
use App\Models\AdCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdCampaignResource extends Resource
{
    protected static ?string $model = AdCampaign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;
    protected static ?string $navigationLabel = 'Reklamlar';
    protected static ?string $modelLabel = 'Reklam';
    protected static ?string $pluralModelLabel = 'Reklamlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Gelir';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdCampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdCampaignsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdCampaigns::route('/'),
            'create' => CreateAdCampaign::route('/create'),
            'edit' => EditAdCampaign::route('/{record}/edit'),
        ];
    }
}
