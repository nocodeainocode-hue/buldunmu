<?php

namespace App\Services;

use App\Models\ClaimInvite;
use App\Models\Company;
use App\Models\Directory;

class ClaimInviteGenerator
{
    /** Seçilen rehber ve filtrelere uyan, davet edilmemiş firmalar için davet üretir; üretilen sayıyı döndürür. */
    public function generate(int $directoryId, ?int $cityId, ?int $categoryId, int $limit, bool $mobileOnly, string $template): int
    {
        $directory = Directory::find($directoryId);

        if (! $directory) {
            return 0;
        }

        // "Bir daha yazma" diyenlerin ve zaten davet edilen numaraların cep haneleri
        $blocked = ClaimInvite::query()->where('status', 'opted_out')->pluck('phone')
            ->map(fn ($phone) => ClaimInvite::mobileDigits($phone))->filter()->flip();
        $invitedPhones = ClaimInvite::query()->whereNotNull('phone')->pluck('phone')
            ->map(fn ($phone) => ClaimInvite::mobileDigits($phone))->filter()->flip();

        $created = 0;

        Company::withoutGlobalScopes()->active()
            ->where('directory_id', $directoryId)
            ->whereNotNull('phone')->where('phone', '!=', '')
            ->when($cityId, fn ($query) => $query->where('city_id', $cityId))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->whereNotIn('id', ClaimInvite::query()->select('company_id'))
            ->whereDoesntHave('owners')
            ->orderBy('id')
            ->chunkById(200, function ($companies) use (&$created, &$blocked, &$invitedPhones, $limit, $mobileOnly, $directory, $template) {
                foreach ($companies as $company) {
                    if ($created >= $limit) {
                        return false;
                    }

                    $digits = ClaimInvite::mobileDigits($company->phone);

                    if ($mobileOnly && ! $digits) {
                        continue;
                    }

                    // Aynı numaraya (başka rehberden de olsa) ikinci davet ve engelli numaralar atlanır.
                    if ($digits && ($blocked->has($digits) || $invitedPhones->has($digits))) {
                        continue;
                    }

                    $token = ClaimInvite::newToken();
                    $link = 'https://'.$directory->domain.'/s/'.$token;

                    ClaimInvite::create([
                        'company_id' => $company->id,
                        'directory_id' => $directory->id,
                        'token' => $token,
                        'phone' => $company->phone,
                        'message' => ClaimInvite::renderMessage($template, $company->name, $directory->name, $link),
                    ]);

                    if ($digits) {
                        $invitedPhones->put($digits, 0);
                    }
                    $created++;
                }

                return true;
            });

        return $created;
    }
}
