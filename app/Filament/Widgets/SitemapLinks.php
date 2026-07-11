<?php

namespace App\Filament\Widgets;

use App\Models\Directory;
use Filament\Widgets\Widget;

class SitemapLinks extends Widget
{
    protected static bool $isDiscovered = false;
    protected string $view = 'filament.widgets.sitemap-links';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $directories = Directory::where('status', 'active')->get();

        $links = collect();

        // Ana site
        $links->push([
            'name' => 'Ana Site (Tüm Rehberler)',
            'url' => url('/sitemap.xml'),
        ]);

        foreach ($directories as $dir) {
            if ($dir->domain) {
                $links->push([
                    'name' => $dir->name,
                    'url' => 'https://' . $dir->domain . '/sitemap.xml',
                ]);
            }
        }

        return ['links' => $links];
    }
}
