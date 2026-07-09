<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function resolveRecord(mixed $key = null): \Illuminate\Database\Eloquent\Model
    {
        return \App\Models\SiteSetting::firstOrCreate(
            [],
            ['site_name' => config('app.name', 'Firma Rehberi')]
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
