<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function resolveRecord(mixed $key = null): Model
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        return SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
            ['directory_id' => $directory?->id],
            ['site_name' => $directory?->name ?? config('app.name', 'Firma Rehberi')],
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
