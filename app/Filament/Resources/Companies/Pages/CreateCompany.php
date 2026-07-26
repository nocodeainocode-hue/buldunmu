<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
