<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['is_global'] ?? false) {
            $data['directory_id'] = null;
        } else {
            $dir = app()->bound('currentDirectory') ? app('currentDirectory') : null;
            if ($dir) {
                $data['directory_id'] = $dir->id;
            }
        }
        unset($data['is_global']);
        return $data;
    }
}
