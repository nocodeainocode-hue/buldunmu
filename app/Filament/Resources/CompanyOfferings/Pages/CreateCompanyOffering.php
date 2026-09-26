<?php

namespace App\Filament\Resources\CompanyOfferings\Pages;

use App\Filament\Resources\CompanyOfferings\CompanyOfferingResource;
use App\Models\Company;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyOffering extends CreateRecord
{
    protected static string $resource = CompanyOfferingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        abort_unless(app()->bound('currentDirectory'), 404);
        $directoryId = app('currentDirectory')->id;
        abort_unless(Company::query()->whereKey($data['company_id'])->where(fn ($query) => $query
            ->whereNull('directory_id')->orWhere('directory_id', $directoryId))->exists(), 422);
        $data['directory_id'] = $directoryId;

        return $data;
    }
}
