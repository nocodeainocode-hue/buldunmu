<?php

namespace App\Filament\Resources\Districts\Pages;

use App\Filament\Resources\Districts\DistrictResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDistrict extends CreateRecord
{
    protected static string $resource = DistrictResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['directory_id'] = null;

        return $data;
    }
}
