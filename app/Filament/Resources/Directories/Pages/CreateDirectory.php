<?php

namespace App\Filament\Resources\Directories\Pages;

use App\Filament\Resources\Directories\DirectoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDirectory extends CreateRecord
{
    protected static string $resource = DirectoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $theme = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'theme_') && $value) {
                $theme[substr($key, 6)] = $value;
                unset($data[$key]);
            }
        }
        $data['theme'] = json_encode($theme);

        return $data;
    }
}
