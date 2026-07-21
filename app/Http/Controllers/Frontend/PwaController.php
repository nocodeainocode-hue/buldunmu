<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Directory;
use App\Models\SiteSetting;
use App\View\Helpers\ThemeHelper;
use Illuminate\Http\JsonResponse;

class PwaController extends Controller
{
    /**
     * Dynamic web app manifest — directory-aware per domain.
     * Route: GET /site.webmanifest
     */
    public function manifest(): JsonResponse
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $settings   = SiteSetting::getSettings();

        $name        = $directory->name ?? $settings->site_name ?? 'Firma Rehberi';
        $shortName   = mb_substr($name, 0, 12);
        $description = $directory->meta_description
            ?? $settings->meta_description
            ?? 'Türkiye genelinde firma, kategori ve şehir araması için sade rehber deneyimi.';

        $icons = $this->buildIcons($directory, $settings);

        $themeColor = ThemeHelper::get('primary', $directory, '#4f46e5');
        $bgColor    = ThemeHelper::get('bg', $directory, '#ffffff');

        $manifest = [
            'name'             => $name,
            'short_name'       => $shortName,
            'description'      => $description,
            'start_url'        => '/?utm_source=pwa',
            'display'          => 'standalone',
            'orientation'      => 'portrait-primary',
            'background_color' => $bgColor,
            'theme_color'      => $themeColor,
            'icons'            => $icons,
            'screenshots'      => $this->buildScreenshots($directory, $settings),
            'lang'             => 'tr',
            'dir'              => 'ltr',
        ];

        return response()->json($manifest, 200, [
            'Content-Type' => 'application/manifest+json',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Build icons array: 192x192, 512x512 with maskable purpose.
     */
    private function buildIcons(?Directory $directory, $settings): array
    {
        $logo    = ($directory->logo ?? null) ?: ($settings->logo ?? null);
        $favicon = ($directory->favicon ?? null) ?: ($settings->favicon ?? null);

        if ($logo) {
            $src  = asset('storage/' . $logo);
            $mime = $this->mimeFromPath($logo);
        } elseif ($favicon) {
            $src  = asset('storage/' . $favicon);
            $mime = $this->mimeFromPath($favicon);
        } else {
            $src  = $this->placeholderIcon($directory, $settings);
            $mime = 'image/svg+xml';
        }

        return [
            [
                'src'     => $src,
                'sizes'   => '192x192',
                'type'    => $mime,
                'purpose' => 'any maskable',
            ],
            [
                'src'     => $src,
                'sizes'   => '512x512',
                'type'    => $mime,
                'purpose' => 'any maskable',
            ],
        ];
    }

    /**
     * Build screenshots array for richer install prompt.
     */
    private function buildScreenshots(?Directory $directory, $settings): array
    {
        $logo    = ($directory->logo ?? null) ?: ($settings->logo ?? null);
        $favicon = ($directory->favicon ?? null) ?: ($settings->favicon ?? null);
        $src     = null;

        if ($logo) {
            $src = asset('storage/' . $logo);
        } elseif ($favicon) {
            $src = asset('storage/' . $favicon);
        }

        if (!$src) {
            return [];
        }

        $name = $directory->name ?? $settings->site_name ?? 'Firma Rehberi';

        return [
            [
                'src'            => $src,
                'sizes'          => '512x512',
                'type'           => 'image/png',
                'form_factor'    => 'wide',
                'label'          => $name . ' ana sayfa',
            ],
        ];
    }

    /**
     * Detect MIME type from file extension.
     */
    private function mimeFromPath(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'svg'  => 'image/svg+xml',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            default => 'image/png',
        };
    }

    /**
     * Generate SVG placeholder icon as data URI.
     */
    private function placeholderIcon(?Directory $directory, $settings): string
    {
        $letter = mb_strtoupper(mb_substr($directory->name ?? $settings->site_name ?? 'F', 0, 1));
        $color  = ThemeHelper::get('primary', $directory, '#4f46e5');
        $svg    = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">'
            . '<rect width="512" height="512" rx="112" fill="' . $color . '"/>'
            . '<text x="256" y="352" font-size="320" font-weight="900" fill="white" text-anchor="middle" font-family="sans-serif">'
            . $letter . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
