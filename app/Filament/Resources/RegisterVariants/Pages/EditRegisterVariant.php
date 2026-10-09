<?php

namespace App\Filament\Resources\RegisterVariants\Pages;

use App\Filament\Resources\RegisterVariants\RegisterVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegisterVariant extends EditRecord
{
    protected static string $resource = RegisterVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
