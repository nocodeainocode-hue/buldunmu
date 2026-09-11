<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\ListSiteSettings;
use App\Filament\Resources\SiteSettings\Schemas\SiteSettingForm;
use App\Filament\Resources\SiteSettings\Tables\SiteSettingsTable;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteSettingResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'Site Ayarları';

    protected static ?string $modelLabel = 'Site Ayarı';

    protected static ?string $pluralModelLabel = 'Site Ayarları';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'site_name';

    public static function form(Schema $schema): Schema
    {
        return SiteSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteSettingsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        return parent::getEloquentQuery()
            ->withoutGlobalScope('directory')
            ->when(
                $directory,
                fn (Builder $query) => $query->where('directory_id', $directory->id),
                fn (Builder $query) => $query->whereRaw('1 = 0'),
            );
    }

    public static function getNavigationUrl(): string
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        if (! $directory) {
            return static::getUrl('index');
        }

        $settings = SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
            ['directory_id' => $directory->id],
            ['site_name' => $directory->name],
        );

        return static::getUrl('edit', ['record' => $settings]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteSettings::route('/'),
            'edit' => EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
