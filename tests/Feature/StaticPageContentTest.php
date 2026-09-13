<?php

namespace Tests\Feature;

use App\Models\Directory;
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
}
