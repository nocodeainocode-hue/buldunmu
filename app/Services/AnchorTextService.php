<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Directory;
use Illuminate\Support\Str;

class AnchorTextService
{
    /**
     * Generate anchor text and link type for a campaign item.
     * Distribution: 35% brand, 20% naked URL, 20% generic, 15% local, 10% service
     */
    public static function generate(Company $company, Directory $directory): array
    {
        $anchorTypes = [
            'brand' => [
                $company->name,
                Str::words($company->name, 1, ''),
                "{$company->name} firması",
            ],
            'naked_url' => [
                $company->website ? parse_url($company->website, PHP_URL_HOST) : null,
                $company->website ?? null,
            ],
            'generic' => [
                'Web sitesini ziyaret edin',
                'Firma web sitesi',
                'Detaylı bilgi alın',
                'İletişime geçin',
                'Firma profili',
            ],
            'local' => [
                ($company->city?->name ?? 'Şehir') . ' ' . ($company->category?->name ?? 'firma'),
                ($company->district?->name ?? 'Bölge') . ' ' . ($company->category?->name ?? 'işletme'),
                $directory->name . ' rehberi',
            ],
            'service' => [
                ($company->category?->name ?? 'Hizmet') . ' hizmeti',
                ($company->category?->name ?? 'Kategori') . ' firmaları',
                'Profesyonel ' . ($company->category?->name ?? 'hizmet'),
            ],
        ];

        // Weighted random selection
        $rand = mt_rand(1, 100);
        if ($rand <= 35) $type = 'brand';
        elseif ($rand <= 55) $type = 'naked_url';
        elseif ($rand <= 75) $type = 'generic';
        elseif ($rand <= 90) $type = 'local';
        else $type = 'service';

        $options = array_filter($anchorTypes[$type]);
        $anchor = $options ? $options[array_rand($options)] : $company->name;

        // Link type distribution: 50% dofollow, 35% nofollow, 10% sponsored, 5% ugc
        $linkRand = mt_rand(1, 100);
        $linkType = match (true) {
            $linkRand <= 50 => 'dofollow',
            $linkRand <= 85 => 'nofollow',
            $linkRand <= 95 => 'sponsored',
            default => 'ugc',
        };

        return [
            'anchor_text' => $anchor,
            'link_type' => $linkType,
        ];
    }
}
