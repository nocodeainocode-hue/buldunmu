<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function resolveRecord(mixed $key = null): Model
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        abort_unless($directory, 404, 'Site ayarlarını düzenlemek için önce bir rehber seçin.');

        $settings = SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
            ['directory_id' => $directory->id],
            ['site_name' => $directory->name],
        );

        if ($settings->site_name !== $directory->name) {
            $settings->update(['site_name' => $directory->name]);
        }

        return $settings;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getHeading(): string
    {
        $directory = app('currentDirectory');

        return $directory->name.' Site Ayarları';
    }

    public function getSubheading(): ?string
    {
        return app('currentDirectory')->domain.' için yapılan değişiklikler yalnızca bu rehberi etkiler.';
    }
}
