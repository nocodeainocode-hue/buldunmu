<?php

namespace App\Filament\Resources\CompanyReviews\Pages;

use App\Filament\Resources\CompanyReviews\CompanyReviewResource;
use App\Models\Company;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyReview extends CreateRecord
{
    protected static string $resource = CompanyReviewResource::class;

    /**
     * Yorum, çoklu-dizin (tenant) kapsamına göre filtrelenir. directory_id boş
     * kalırsa kayıt aktif dizinin listesinde görünmez ve create sonrası edit/liste
     * çözümlemesi global scope yüzünden kaydı bulamaz (hata). Bu yüzden yorumun
     * dizinini önce aktif dizinden, yoksa seçilen firmadan türetiyoruz.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $directoryId = app()->bound('currentDirectory')
            ? app('currentDirectory')->id
            : Company::query()->withoutGlobalScope('directory')->whereKey($data['company_id'] ?? null)->first()?->directory_id;

        $data['directory_id'] = $directoryId;

        return $data;
    }
}
