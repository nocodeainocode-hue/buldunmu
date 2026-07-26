<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $dir = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        if ($dir) {
            $data['directory_id'] = $dir->id;
        }
        return $data;
    }
}
