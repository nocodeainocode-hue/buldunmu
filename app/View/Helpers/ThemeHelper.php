<?php

namespace App\View\Helpers;

use App\Models\Directory;

class ThemeHelper
{
    // ═══ 20 TEMPLATE KONFIGÜRASYONU ═══
    // Her biri farklı hero+kart+grid+sıralama kombinasyonu
    // 5 layout dosyasıyla (default,modern,minimal,bold,elegant) 20 farklı görünüm
    
    const TEMPLATES = [
        // ── Layout 1: default.blade.php ──
        'default' => ['name'=>'Klasik Rehber','hero'=>'gradient','card'=>'default','grid'=>'3','order'=>'categories,cities,premium,latest','width'=>'1280px','layout'=>'default','primary'=>'#6366f1','primary_hover'=>'#4f46e5','primary_light'=>'#eef2ff','secondary'=>'#8b5cf6','accent'=>'#f59e0b','bg'=>'#f9fafb','bg_card'=>'#ffffff','text'=>'#111827','text_muted'=>'#6b7280','border'=>'#f3f4f6','border_radius'=>'1rem','font_body'=>'Inter,sans-serif','font_heading'=>'Poppins,sans-serif','hero_from'=>'#6366f1','hero_to'=>'#8b5cf6','card_shadow'=>'0 1px 3px rgba(0,0,0,0.08)'],
        'premium-showcase' => ['name'=>'Premium Vitrini','hero'=>'gradient','card'=>'visual','grid'=>'3','order'=>'premium,latest,categories,cities','width'=>'1280px','layout'=>'default','primary'=>'#7c3aed','primary_hover'=>'#6d28d9','primary_light'=>'#f5f3ff','secondary'=>'#a78bfa','accent'=>'#f59e0b','bg'=>'#faf5ff','bg_card'=>'#ffffff','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#ede9fe','border_radius'=>'1.25rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#7c3aed','hero_to'=>'#c084fc','card_shadow'=>'0 4px 12px rgba(124,58,237,0.15)'],
        'corporate' => ['name'=>'Kurumsal Katalog','hero'=>'gradient','card'=>'default','grid'=>'4','order'=>'categories,premium,cities,latest','width'=>'1120px','layout'=>'default','primary'=>'#1e3a5f','primary_hover'=>'#15294a','primary_light'=>'#e8f0fe','secondary'=>'#334155','accent'=>'#c9a84c','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#64748b','border'=>'#e2e8f0','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#1e3a5f','hero_to'=>'#2d5a8a','card_shadow'=>'0 1px 2px rgba(0,0,0,0.04)'],
        'landing' => ['name'=>'Landing+Rehber','hero'=>'gradient','card'=>'default','grid'=>'3','order'=>'premium,categories,latest,cities','width'=>'1280px','layout'=>'default','primary'=>'#059669','primary_hover'=>'#047857','primary_light'=>'#ecfdf5','secondary'=>'#10b981','accent'=>'#f59e0b','bg'=>'#f0fdf4','bg_card'=>'#ffffff','text'=>'#111827','text_muted'=>'#6b7280','border'=>'#d1fae5','border_radius'=>'0.75rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#059669','hero_to'=>'#06b6d4','card_shadow'=>'0 1px 3px rgba(0,0,0,0.06)'],
        
        // ── Layout 2: modern.blade.php ──
        'modern' => ['name'=>'Modern Is Odakli','hero'=>'split','card'=>'horizontal','grid'=>'1','order'=>'premium,categories,latest','width'=>'1280px','layout'=>'modern','primary'=>'#0f172a','primary_hover'=>'#1e293b','primary_light'=>'#f1f5f9','secondary'=>'#334155','accent'=>'#06b6d4','bg'=>'#ffffff','bg_card'=>'#ffffff','text'=>'#0f172a','text_muted'=>'#64748b','border'=>'#e2e8f0','border_radius'=>'0.25rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#0f172a','hero_to'=>'#1e293b','card_shadow'=>'none'],
        'dashboard' => ['name'=>'Dashboard Tarzi','hero'=>'split','card'=>'horizontal','grid'=>'1','order'=>'premium,latest,categories','width'=>'100%','layout'=>'modern','primary'=>'#2563eb','primary_hover'=>'#1d4ed8','primary_light'=>'#eff6ff','secondary'=>'#3b82f6','accent'=>'#10b981','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#94a3b8','border'=>'#e2e8f0','border_radius'=>'0.375rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#1e3a5f','hero_to'=>'#2563eb','card_shadow'=>'0 1px 2px rgba(0,0,0,0.04)'],
        'split-hero' => ['name'=>'Split Screen','hero'=>'split','card'=>'horizontal','grid'=>'1','order'=>'premium,categories,latest,cities','width'=>'1280px','layout'=>'modern','primary'=>'#be123c','primary_hover'=>'#9f1239','primary_light'=>'#fff1f2','secondary'=>'#e11d48','accent'=>'#fbbf24','bg'=>'#ffffff','bg_card'=>'#ffffff','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#fecdd3','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#be123c','hero_to'=>'#881337','card_shadow'=>'0 2px 8px rgba(190,18,60,0.1)'],
        'magazine' => ['name'=>'Magazine+Hibrit','hero'=>'split','card'=>'horizontal','grid'=>'2','order'=>'premium,latest,categories','width'=>'1120px','layout'=>'modern','primary'=>'#92400e','primary_hover'=>'#78350f','primary_light'=>'#fef3c7','secondary'=>'#d97706','accent'=>'#f59e0b','bg'=>'#fffbeb','bg_card'=>'#ffffff','text'=>'#292524','text_muted'=>'#78716c','border'=>'#fde68a','border_radius'=>'0.5rem','font_body'=>'Georgia,serif','font_heading'=>'Georgia,serif','hero_from'=>'#92400e','hero_to'=>'#b45309','card_shadow'=>'0 2px 8px rgba(0,0,0,0.06)'],
        
        // ── Layout 3: minimal.blade.php ──
        'minimal' => ['name'=>'Minimal Arama','hero'=>'minimal','card'=>'default','grid'=>'2','order'=>'premium,latest,categories','width'=>'900px','layout'=>'minimal','primary'=>'#374151','primary_hover'=>'#4b5563','primary_light'=>'#f3f4f6','secondary'=>'#6b7280','accent'=>'#10b981','bg'=>'#ffffff','bg_card'=>'#fafafa','text'=>'#374151','text_muted'=>'#9ca3af','border'=>'#f3f4f6','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#374151','hero_to'=>'#6b7280','card_shadow'=>'none'],
        'search-first' => ['name'=>'Search-First','hero'=>'minimal','card'=>'default','grid'=>'2','order'=>'latest,premium,categories','width'=>'900px','layout'=>'minimal','primary'=>'#0f766e','primary_hover'=>'#0d6b63','primary_light'=>'#f0fdfa','secondary'=>'#14b8a6','accent'=>'#14b8a6','bg'=>'#ffffff','bg_card'=>'#fafafa','text'=>'#111827','text_muted'=>'#9ca3af','border'=>'#e5e7eb','border_radius'=>'0.375rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#0f766e','hero_to'=>'#06b6d4','card_shadow'=>'none'],
        'mobile-app' => ['name'=>'Mobil Uygulama','hero'=>'minimal','card'=>'default','grid'=>'2','order'=>'premium,latest,categories','width'=>'900px','layout'=>'minimal','primary'=>'#4f46e5','primary_hover'=>'#4338ca','primary_light'=>'#eef2ff','secondary'=>'#818cf8','accent'=>'#f472b6','bg'=>'#fafafe','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#94a3b8','border'=>'#f1f5f9','border_radius'=>'1rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#4f46e5','hero_to'=>'#818cf8','card_shadow'=>'0 2px 8px rgba(0,0,0,0.04)'],
        'step-by-step' => ['name'=>'Adim Adim Bulma','hero'=>'minimal','card'=>'default','grid'=>'2','order'=>'premium,latest,categories,cities','width'=>'900px','layout'=>'minimal','primary'=>'#c0262d','primary_hover'=>'#b91c1c','primary_light'=>'#fef2f2','secondary'=>'#ef4444','accent'=>'#fbbf24','bg'=>'#ffffff','bg_card'=>'#fef2f2','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#fecaca','border_radius'=>'0.75rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#c0262d','hero_to'=>'#ef4444','card_shadow'=>'0 2px 8px rgba(0,0,0,0.04)'],
        
        // ── Layout 4: bold.blade.php ──
        'bold' => ['name'=>'Cesur Vitrin','hero'=>'gradient','card'=>'visual','grid'=>'3','order'=>'premium,latest,categories','width'=>'100%','layout'=>'bold','primary'=>'#dc2626','primary_hover'=>'#b91c1c','primary_light'=>'#fef2f2','secondary'=>'#f97316','accent'=>'#facc15','bg'=>'#fef2f2','bg_card'=>'#ffffff','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#fecaca','border_radius'=>'1.25rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#dc2626','hero_to'=>'#f97316','card_shadow'=>'0 4px 12px rgba(220,38,38,0.15)'],
        'comparison' => ['name'=>'Karsilastirma','hero'=>'gradient','card'=>'default','grid'=>'3','order'=>'premium,latest,categories,cities','width'=>'100%','layout'=>'bold','primary'=>'#0369a1','primary_hover'=>'#075985','primary_light'=>'#f0f9ff','secondary'=>'#38bdf8','accent'=>'#f59e0b','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#64748b','border'=>'#e2e8f0','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#0369a1','hero_to'=>'#0284c7','card_shadow'=>'0 2px 8px rgba(0,0,0,0.06)'],
        'timeline' => ['name'=>'Zaman Akisi','hero'=>'gradient','card'=>'default','grid'=>'1','order'=>'latest,premium,categories','width'=>'900px','layout'=>'bold','primary'=>'#15803d','primary_hover'=>'#166534','primary_light'=>'#f0fdf4','secondary'=>'#22c55e','accent'=>'#a3e635','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#dcfce7','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#15803d','hero_to'=>'#22c55e','card_shadow'=>'0 1px 3px rgba(0,0,0,0.06)'],
        'mosaic' => ['name'=>'Kart Mozaik','hero'=>'gradient','card'=>'visual','grid'=>'3','order'=>'premium,latest,categories','width'=>'100%','layout'=>'bold','primary'=>'#9333ea','primary_hover'=>'#7e22ce','primary_light'=>'#faf5ff','secondary'=>'#c084fc','accent'=>'#fbbf24','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1f2937','text_muted'=>'#6b7280','border'=>'#f3e8ff','border_radius'=>'1rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#9333ea','hero_to'=>'#a855f7','card_shadow'=>'0 4px 16px rgba(147,51,234,0.12)'],
        
        // ── Layout 5: elegant.blade.php ──
        'elegant' => ['name'=>'Elegant Premium','hero'=>'gradient','card'=>'visual','grid'=>'3','order'=>'categories,premium,latest,cities','width'=>'1120px','layout'=>'elegant','primary'=>'#1e3a5f','primary_hover'=>'#15294a','primary_light'=>'#e8f0fe','secondary'=>'#c9a84c','accent'=>'#c9a84c','bg'=>'#faf9f6','bg_card'=>'#ffffff','text'=>'#2d3748','text_muted'=>'#718096','border'=>'#e8e0d3','border_radius'=>'0.75rem','font_body'=>'Georgia,serif','font_heading'=>'Georgia,serif','hero_from'=>'#1e3a5f','hero_to'=>'#2d5a8a','card_shadow'=>'0 2px 12px rgba(0,0,0,0.06)'],
        'city-focused' => ['name'=>'Sehir Odakli','hero'=>'gradient','card'=>'default','grid'=>'3','order'=>'cities,categories,premium,latest','width'=>'1280px','layout'=>'elegant','primary'=>'#b45309','primary_hover'=>'#92400e','primary_light'=>'#fffbeb','secondary'=>'#d97706','accent'=>'#fcd34d','bg'=>'#fff7ed','bg_card'=>'#ffffff','text'=>'#431407','text_muted'=>'#9a3412','border'=>'#fed7aa','border_radius'=>'0.75rem','font_body'=>'Georgia,serif','font_heading'=>'Georgia,serif','hero_from'=>'#b45309','hero_to'=>'#ea580c','card_shadow'=>'0 2px 8px rgba(0,0,0,0.04)'],
        'category-mega' => ['name'=>'Kategori Mega','hero'=>'gradient','card'=>'default','grid'=>'4','order'=>'categories,premium,latest','width'=>'1280px','layout'=>'elegant','primary'=>'#6d28d9','primary_hover'=>'#5b21b6','primary_light'=>'#f5f3ff','secondary'=>'#a78bfa','accent'=>'#fcd34d','bg'=>'#fafafe','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#64748b','border'=>'#e2e8f0','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#6d28d9','hero_to'=>'#8b5cf6','card_shadow'=>'0 1px 3px rgba(0,0,0,0.06)'],
        'map-first' => ['name'=>'Harita Odakli','hero'=>'gradient','card'=>'default','grid'=>'2','order'=>'premium,cities,latest,categories','width'=>'1280px','layout'=>'elegant','primary'=>'#0d9488','primary_hover'=>'#0f766e','primary_light'=>'#f0fdfa','secondary'=>'#5eead4','accent'=>'#fbbf24','bg'=>'#f8fafc','bg_card'=>'#ffffff','text'=>'#1e293b','text_muted'=>'#64748b','border'=>'#ccfbf1','border_radius'=>'0.5rem','font_body'=>'Inter,sans-serif','font_heading'=>'Inter,sans-serif','hero_from'=>'#0d9488','hero_to'=>'#14b8a6','card_shadow'=>'0 1px 3px rgba(0,0,0,0.06)'],
    ];

    public static function googleFontsUrl(?Directory $directory = null): string
    {
        $font = self::get('font_body', $directory, 'Inter,sans-serif');
        $heading = self::get('font_heading', $directory, 'Inter,sans-serif');

        $fonts = collect([$font, $heading])
            ->map(fn($f) => explode(',', $f)[0] ?? 'Inter')
            ->unique()
            ->filter(fn($f) => !in_array($f, ['sans-serif', 'serif', 'monospace', 'Georgia', 'Arial']))
            ->map(fn($f) => 'family=' . urlencode(trim($f, "'\" ")))
            ->implode('&');

        return $fonts ? 'https://fonts.googleapis.com/css2?' . $fonts . '&display=swap' : '';
    }
    public static function get(string $key, ?Directory $directory = null, string $default = ''): string
    {
        $template = $directory->template ?? 'default';
        $cfg = self::TEMPLATES[$template] ?? self::TEMPLATES['default'];
        return $cfg[$key] ?? $default;
    }

    public static function layoutFile(?Directory $directory = null): string
    {
        return self::get('layout', $directory, 'default');
    }

    public static function templateSelectOptions(): array
    {
        $opts = [];
        foreach (self::TEMPLATES as $key => $cfg) {
            $opts[$key] = $cfg['name'];
        }
        return $opts;
    }

    // ── Existing methods ──
    public static function cssVariables(?Directory $directory = null): string
    {
        $cfg = self::TEMPLATES[$directory->template ?? 'default'] ?? self::TEMPLATES['default'];
        $lines = [':root {'];
        foreach ($cfg as $k => $v) {
            if ($k === 'width') {
                $lines[] = "    --page_width: {$v};";
                continue;
            }

            if (in_array($k, ['name','hero','card','grid','order','layout'])) continue;
            $lines[] = "    --{$k}: {$v};";
        }
        if (isset($cfg['hero_from'])) {
            $lines[] = "    --hero_gradient_from: {$cfg['hero_from']};";
        }
        if (isset($cfg['hero_to'])) {
            $lines[] = "    --hero_gradient_to: {$cfg['hero_to']};";
        }
        $lines[] = '}';
        return implode("\n", $lines);
    }
    public static function templateClass(?Directory $directory = null): string { return ''; }
    public static function heroPartial(?Directory $directory = null): string { return self::get('hero',$directory,'gradient'); }
    public static function cardPartial(?Directory $directory = null): string { return self::get('card',$directory,'default'); }
    public static function sectionOrder(?Directory $directory = null): array { return explode(',',self::get('order',$directory)); }
    public static function gridCols(?Directory $directory = null): string { $c=self::get('grid',$directory,'3'); return match($c){'1'=>'grid-cols-1','2'=>'grid-cols-1 sm:grid-cols-2','3'=>'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3','4'=>'grid-cols-2 sm:grid-cols-3 md:grid-cols-4',default=>'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'}; }
}
