<?php

namespace Tests\Unit;

use App\View\Helpers\ThemeHelper;
use App\Models\Directory;
use Tests\TestCase;

class ThemeHelperTest extends TestCase
{
    public function test_template_config_has_required_keys(): void
    {
        $default = ThemeHelper::TEMPLATES['default'];
        $this->assertArrayHasKey('name', $default);
        $this->assertArrayHasKey('primary', $default);
        $this->assertArrayHasKey('bg', $default);
        $this->assertArrayHasKey('text', $default);
        $this->assertArrayHasKey('font_body', $default);
        $this->assertArrayHasKey('hero_from', $default);
        $this->assertArrayHasKey('border_radius', $default);
        $this->assertArrayHasKey('card_shadow', $default);
    }

    public function test_get_returns_correct_config_value(): void
    {
        $this->assertEquals('default', ThemeHelper::get('layout'));
        $this->assertEquals('#6366f1', ThemeHelper::get('primary'));
        $this->assertEquals('fallback', ThemeHelper::get('nonexistent', null, 'fallback'));
    }

    public function test_new_layout_templates_are_registered(): void
    {
        foreach ([
            'pocket-directory', 'service-console', 'decision-desk',
            'city-board', 'craft-market', 'quick-quote', 'district-showcase', 'sector-exchange',
        ] as $template) {
            $this->assertArrayHasKey($template, ThemeHelper::TEMPLATES);
            $this->assertSame($template, ThemeHelper::TEMPLATES[$template]['layout']);
            $this->assertFileExists(resource_path('views/frontend/home/' . $template . '.blade.php'));
        }
    }

    public function test_css_variables_contain_key_properties(): void
    {
        $css = ThemeHelper::cssVariables(null);
        $this->assertStringContainsString('--primary', $css);
        $this->assertStringContainsString('--bg', $css);
        $this->assertStringContainsString('--text', $css);
        $this->assertStringContainsString('--font_body', $css);
        $this->assertStringContainsString('--hero_gradient_from', $css);
        $this->assertStringContainsString('--border_radius', $css);
    }

    public function test_google_fonts_url_generates(): void
    {
        $url = ThemeHelper::googleFontsUrl(null);
        $this->assertStringContainsString('fonts.googleapis.com', $url);
        $this->assertStringContainsString('family=Inter', $url);
    }

    public function test_template_select_options_returns_labels(): void
    {
        $opts = ThemeHelper::templateSelectOptions();
        $this->assertArrayHasKey('default', $opts);
        $this->assertEquals('Klasik Rehber', $opts['default']);
        $this->assertEquals('Cesur Vitrin', $opts['bold']);
    }

    public function test_grid_cols_returns_tailwind_classes(): void
    {
        $this->assertStringContainsString('grid-cols', ThemeHelper::gridCols());
    }

    public function test_layout_file_resolves_from_directory(): void
    {
        $dir = new Directory(['template' => 'bold']);
        $this->assertEquals('bold', ThemeHelper::layoutFile($dir));

        $dir2 = new Directory(['template' => null]);
        $this->assertEquals('default', ThemeHelper::layoutFile($dir2));
    }

    public function test_card_partial_resolves_correctly(): void
    {
        $dir = new Directory(['template' => 'premium-showcase']);
        $this->assertEquals('visual', ThemeHelper::cardPartial($dir));

        $dir2 = new Directory(['template' => 'modern']);
        $this->assertEquals('horizontal', ThemeHelper::cardPartial($dir2));
    }
}
