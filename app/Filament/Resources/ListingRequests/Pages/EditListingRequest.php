<?php

namespace App\Filament\Resources\ListingRequests\Pages;

use App\Filament\Resources\ListingRequests\ListingRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditListingRequest extends EditRecord
{
    protected static string $resource = ListingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
