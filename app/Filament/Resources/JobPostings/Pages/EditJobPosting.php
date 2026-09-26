<?php

namespace App\Filament\Resources\JobPostings\Pages;

use App\Filament\Resources\JobPostings\JobPostingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobPosting extends EditRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['admin_published'] = true;
        $data['published_at'] = $data['status'] === 'published'
            ? ($this->record->published_at ?: now())
            : null;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
