<?php

namespace Tests\Feature;

use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DirectoryBrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_install_preserves_custom_logos_unless_requested_and_is_repeatable(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $brands = json_decode(file_get_contents(resource_path('branding/directories/manifest.json')), true);
        foreach ($brands as $brand) {
            Directory::create(['name' => $brand['name'], 'slug' => $brand['directory'], 'domain' => $brand['domain'], 'status' => 'active']);
        }
        $custom = Directory::where('domain', 'esnafy.com.tr')->firstOrFail();
        $custom->update(['logo' => 'custom/esnafy.svg', 'favicon' => 'custom/esnafy.png']);
        $this->artisan('directories:install-branding')->assertSuccessful();
        $this->assertNull(Directory::where('domain', 'firmakonum.com.tr')->firstOrFail()->logo);
        $this->artisan('directories:install-branding', ['--apply' => true])->assertSuccessful();
        $this->assertSame('custom/esnafy.svg', $custom->fresh()->logo);
        $this->artisan('directories:install-branding', ['--apply' => true, '--replace-existing' => true])->assertSuccessful();
        $this->assertSame('directories/branding/2026-10/esnafy/logo.svg', $custom->fresh()->logo);
        foreach ($brands as $brand) {
            $directory = Directory::where('domain', $brand['domain'])->firstOrFail();
            $this->assertStringContainsString($brand['directory'].'/logo.svg', $directory->logo);
            Storage::disk('public')->assertExists([$directory->logo, $directory->favicon, dirname($directory->logo).'/icon-512.png']);
        }
        $backups = Storage::disk('local')->files('branding-backups');
        $this->assertCount(2, $backups);
        $this->assertTrue(collect($backups)->contains(fn ($path) => collect(json_decode(Storage::disk('local')->get($path), true))->contains('logo', 'custom/esnafy.svg')));
        $this->artisan('directories:install-branding', ['--apply' => true, '--replace-existing' => true])->assertSuccessful();
        $this->assertCount(2, Storage::disk('local')->files('branding-backups'));
        app()->instance('currentDirectory', $custom->fresh());
        $response = $this->get('/site.webmanifest')->assertOk();
        $this->assertStringEndsWith('/esnafy/icon-192.png', $response->json('icons.0.src'));
        $this->assertSame('image/png', $response->json('icons.0.type'));
    }

    public function test_missing_directory_leaves_files_and_records_unchanged(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $directory = Directory::create(['name' => '81 İl', 'slug' => '81il', 'domain' => '81ilfirmalar.com.tr']);
        $this->artisan('directories:install-branding', ['--apply' => true])->assertFailed();
        $this->assertNull($directory->fresh()->logo);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }
}
