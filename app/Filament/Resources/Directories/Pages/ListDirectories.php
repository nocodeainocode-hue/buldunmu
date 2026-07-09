<?php

namespace App\Filament\Resources\Directories\Pages;

use App\Filament\Resources\Directories\DirectoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectories extends ListRecords
{
    protected static string $resource = DirectoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
