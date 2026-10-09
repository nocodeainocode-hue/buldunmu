<?php

namespace App\Filament\Resources\RegisterVariants\Pages;

use App\Filament\Resources\RegisterVariants\RegisterVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegisterVariants extends ListRecords
{
    protected static string $resource = RegisterVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
