<?php

namespace App\Filament\Resources\ListingRequests\Pages;

use App\Filament\Resources\ListingRequests\ListingRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListListingRequests extends ListRecords
{
    protected static string $resource = ListingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
