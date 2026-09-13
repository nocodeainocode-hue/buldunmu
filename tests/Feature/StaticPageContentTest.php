<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticPageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_rich_editor_privacy_content_uses_the_directory_default(): void
    {
        $directory = Directory::create([
            'name' => 'Kobiva',
            'slug' => 'kobiva',
            'domain' => 'kobiva.test',
            'status' => 'active',
            'page_contents' => ['privacy' => '<p><br></p>'],
        ]);
        app()->instance('currentDirectory', $directory);

        $this->get('/gizlilik-politikasi')
            ->assertOk()
            ->assertSee('Kobiva tarafından toplanan bilgilerin');
    }

    public function test_repair_command_resets_legacy_generated_content_to_the_dynamic_default(): void
    {
        $directory = Directory::create([
            'name' => 'Kobiva',
            'slug' => 'kobiva',
            'domain' => 'kobiva.test',
            'status' => 'active',
            'page_contents' => ['privacy' => '<p>İşletme Bulvarı gizlilik politikası.</p>'],
        ]);

        $this->artisan('directories:repair-page-contents')
            ->assertSuccessful();

        $this->assertArrayNotHasKey('privacy', $directory->fresh()->page_contents);
    }

    public function test_directory_uses_its_own_settings_instead_of_global_branding(): void
    {
        SiteSetting::create([
            'site_name' => 'İşletme Bulvarı',
            'homepage_title' => 'Güvenilir Firma Rehberi',
        ]);

        $directory = Directory::create([
            'name' => 'Kobiva',
            'slug' => 'kobiva',
            'domain' => 'kobiva.test',
            'status' => 'active',
        ]);
        app()->instance('currentDirectory', $directory);

        $settings = SiteSetting::getSettings();

        $this->assertSame('Kobiva', $settings->site_name);
        $this->assertSame($directory->id, $settings->directory_id);
        $this->assertSame('Kobiva - Aradiginiz Firmayi Bulun', $settings->homepage_title);
    }
}
