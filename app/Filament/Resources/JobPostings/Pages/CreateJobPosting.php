<?php

namespace App\Filament\Resources\JobPostings\Pages;

use App\Filament\Resources\JobPostings\JobPostingResource;
use App\Models\Company;
use Filament\Resources\Pages\CreateRecord;

class CreateJobPosting extends CreateRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        abort_unless(app()->bound('currentDirectory'), 404);
        $directoryId = app('currentDirectory')->id;
        abort_unless(Company::query()->whereKey($data['company_id'])->where(fn ($query) => $query
            ->whereNull('directory_id')->orWhere('directory_id', $directoryId))->exists(), 422);
        $data['directory_id'] = $directoryId;
        $data['admin_published'] = true;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        return $data;
    }
}
