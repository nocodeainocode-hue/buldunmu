<?php

namespace App\Filament\Resources\CompanyOfferings\Pages;

use App\Filament\Resources\CompanyOfferings\CompanyOfferingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanyOfferings extends ListRecords
{
    protected static string $resource = CompanyOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
