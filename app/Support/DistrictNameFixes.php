<?php

namespace App\Support;

use App\Models\City;
use Illuminate\Support\Str;

/**
 * Açık ilçe veri kaynağı bazı adları bozuk veriyor: noktasız ı yerine i ("Sarioğlan", "Hacilar", "Akkişla")
 * ve "İ" harfinden kalan birleşik nokta işareti ("Karai̇sali"). Düzeltme tablosu
 * database/content/district_name_fixes.json içindedir (şehir-slug/ilçe-slug => doğru ad).
 */
class DistrictNameFixes
{
    /** @var array<string, string>|null */
    private static ?array $map = null;

    public static function fix(City $city, string $name): string
    {
        // "i̇" gibi birleşik nokta işaretini kaldır (İ harfinin küçük harfe yanlış çevrilmesinden kalır).
        $name = trim(str_replace("\u{0307}", '', $name));
        $slug = Str::slug($name);

        // Merkez ilçe şehirle aynı adı taşır; şehrin kendi (doğru yazımlı) adı kullanılır.
        if ($slug !== '' && $slug === $city->slug) {
            return $city->name;
        }

        return self::map()[$city->slug.'/'.$slug] ?? $name;
    }

    /** @return array<string, string> */
    private static function map(): array
    {
        if (self::$map === null) {
            $path = database_path('content/district_name_fixes.json');
            self::$map = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
        }

        return self::$map;
    }
}
