<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        return app()->bound('currentDirectory')
            ? app('currentDirectory')->name.' Site Ayarları'
            : 'Site Ayarları - Önce Rehber Seçin';
    }
}
