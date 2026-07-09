<?php

namespace App\Filament\Resources\DiscoveredCompanies;

use App\Filament\Resources\DiscoveredCompanies\Pages\DiscoverCompanies;
use App\Filament\Resources\DiscoveredCompanies\Pages\ImportCompanies;
use App\Filament\Resources\DiscoveredCompanies\Pages\ListDiscoveredCompanies;
use App\Filament\Resources\DiscoveredCompanies\Tables\DiscoveredCompaniesTable;
use App\Models\DiscoveredCompany;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class DiscoveredCompanyResource extends Resource
{
    protected static ?string $model = DiscoveredCompany::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Toplu Firma Keşfi';

    protected static ?string $modelLabel = 'Keşfedilen Firma';

    protected static ?string $pluralModelLabel = 'Keşfedilen Firmalar';

    protected static string|\UnitEnum|null $navigationGroup = 'Firma Yönetimi';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return DiscoveredCompaniesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscoveredCompanies::route('/'),
            'discover' => DiscoverCompanies::route('/kesfet'),
            'import' => ImportCompanies::route('/import'),
        ];
    }
}
