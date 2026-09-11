<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! app()->bound('currentDirectory')) {
            throw ValidationException::withMessages([
                'name' => 'Firma oluşturmadan önce üst menüden hedef rehberi seçin.',
            ]);
        }

        $data['directory_id'] = app('currentDirectory')->id;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
