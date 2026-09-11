<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_directory_uses_shared_taxonomies_without_copying_them(): void
    {
        $source = Directory::create([
            'name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active',
        ]);
        Category::create([
            'name' => 'Eski Özel Kategori', 'slug' => 'eski-ozel', 'status' => 'active', 'directory_id' => $source->id,
        ]);
        City::create([
            'name' => 'Eski Özel Şehir', 'slug' => 'eski-ozel-sehir', 'directory_id' => $source->id,
        ]);

        $directory = Directory::create([
            'name' => 'Yeni Rehber', 'slug' => 'yeni-rehber', 'domain' => 'https://www.YENI.test/path', 'status' => 'active',
        ]);

        $this->assertSame('yeni.test', $directory->domain);
        $this->assertDatabaseMissing('categories', ['directory_id' => $directory->id]);
        $this->assertDatabaseMissing('cities', ['directory_id' => $directory->id]);
        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $directory->id,
            'site_name' => 'Yeni Rehber',
        ]);
    }

    public function test_directory_specific_settings_are_preferred_over_global_settings(): void
    {
        $directory = Directory::create([
            'name' => 'Özel Rehber', 'slug' => 'ozel-rehber', 'domain' => 'ozel.test', 'status' => 'active',
        ]);
        SiteSetting::withoutGlobalScope('directory')->create([
            'directory_id' => null,
            'site_name' => 'Genel Ayar',
        ]);
        SiteSetting::withoutGlobalScope('directory')
            ->where('directory_id', $directory->id)
            ->update(['site_name' => 'Özel Ayar']);

        app()->instance('currentDirectory', $directory);

        $this->assertSame('Özel Ayar', SiteSetting::getSettings()->site_name);
    }

    public function test_inactive_directory_is_not_resolved_from_its_domain(): void
    {
        Directory::create([
            'name' => 'Pasif Rehber', 'slug' => 'pasif-rehber', 'domain' => 'pasif.test', 'status' => 'passive',
        ]);

        $this->withServerVariables(['HTTP_HOST' => 'pasif.test'])
            ->get('http://pasif.test/')
            ->assertOk();

        $this->assertFalse(app()->bound('currentDirectory'));
    }
}
