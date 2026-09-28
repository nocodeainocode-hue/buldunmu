<?php

namespace App\Support;

use Illuminate\Support\Str;

class UploadFileName
{
    /**
     * Convert an original filename to an SEO-friendly storage name.
     * Preserves the human-readable slug, appends a short random suffix
     * to prevent collisions, and keeps the file extension lowercase.
     *
     * Example: "81ilfirmalar-firma-rehberi.webp" → "81ilfirmalar-firma-rehberi-k3mf.webp"
     */
    public static function seoFriendly(string $originalName): string
    {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION) ?: 'jpg');
        $base = pathinfo($originalName, PATHINFO_FILENAME);

        // Turkish characters → ASCII
        $base = strtr($base, [
            'ç' => 'c', 'Ç' => 'C',
            'ğ' => 'g', 'Ğ' => 'G',
            'ı' => 'i', 'İ' => 'I',
            'ö' => 'o', 'Ö' => 'O',
            'ş' => 's', 'Ş' => 'S',
            'ü' => 'u', 'Ü' => 'U',
        ]);

        $slug = Str::slug($base, '-');

        if (blank($slug)) {
            $slug = 'gorsel';
        }

        // Truncate very long names (max 80 chars for the slug part)
        if (strlen($slug) > 80) {
            $slug = rtrim(substr($slug, 0, 80), '-');
        }

        // 4-char random suffix for collision safety
        $suffix = Str::lower(Str::random(4));

        return $slug . '-' . $suffix . '.' . $ext;
    }
}
