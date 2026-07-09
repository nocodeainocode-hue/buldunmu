<?php

namespace App\Filament\Resources\Directories\Pages;

use App\Filament\Resources\Directories\DirectoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDirectory extends EditRecord
{
    protected static string $resource = DirectoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $theme = is_array($data['theme'] ?? null) ? $data['theme'] : json_decode($data['theme'] ?? '{}', true);

        foreach ($theme as $key => $value) {
            $data['theme_' . $key] = $value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
