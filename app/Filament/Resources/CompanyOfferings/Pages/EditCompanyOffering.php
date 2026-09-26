<?php

namespace App\Filament\Resources\CompanyOfferings\Pages;

use App\Filament\Resources\CompanyOfferings\CompanyOfferingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyOffering extends EditRecord
{
    protected static string $resource = CompanyOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
