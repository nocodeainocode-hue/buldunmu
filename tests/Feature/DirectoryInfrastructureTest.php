<?php

namespace Tests\Feature;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\SiteSetting;
use App\Models\User;
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
            'homepage_title' => 'Genel Başlık',
        ]);
        SiteSetting::withoutGlobalScope('directory')
            ->where('directory_id', $directory->id)
            ->update(['homepage_title' => 'Özel Başlık']);

        app()->instance('currentDirectory', $directory);

        $this->assertSame('Özel Başlık', SiteSetting::getSettings()->homepage_title);
        $this->assertSame('Özel Rehber', SiteSetting::getSettings()->site_name);
    }

    public function test_site_settings_admin_query_and_update_are_isolated_to_selected_directory(): void
    {
        $first = Directory::create([
            'name' => 'Birinci Rehber', 'slug' => 'birinci', 'domain' => 'birinci.test', 'status' => 'active',
        ]);
        $second = Directory::create([
            'name' => 'İkinci Rehber', 'slug' => 'ikinci', 'domain' => 'ikinci.test', 'status' => 'active',
        ]);

        app()->instance('currentDirectory', $first);

        $visibleSettings = SiteSettingResource::getEloquentQuery()->get();
        $this->assertCount(1, $visibleSettings);
        $this->assertSame($first->id, $visibleSettings->first()->directory_id);

        $visibleSettings->first()->update([
            'homepage_title' => 'Birinci Yeni Başlık',
            'phone' => '111',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $first->id,
            'homepage_title' => 'Birinci Yeni Başlık',
            'phone' => '111',
        ]);
        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $second->id,
            'site_name' => 'İkinci Rehber',
            'phone' => null,
        ]);
    }

    public function test_directory_name_change_keeps_internal_site_name_in_sync(): void
    {
        $directory = Directory::create([
            'name' => 'Eski İsim', 'slug' => 'eski-isim', 'domain' => 'isim.test', 'status' => 'active',
        ]);

        $directory->update(['name' => 'Yeni İsim']);

        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $directory->id,
            'site_name' => 'Yeni İsim',
        ]);
    }

    public function test_site_settings_admin_query_is_empty_without_a_selected_directory(): void
    {
        Directory::create([
            'name' => 'Rehber', 'slug' => 'rehber', 'domain' => 'rehber.test', 'status' => 'active',
        ]);

        $this->assertFalse(app()->bound('currentDirectory'));
        $this->assertCount(0, SiteSettingResource::getEloquentQuery()->get());
    }

    public function test_tenant_switch_rejects_an_unknown_directory_id(): void
    {
        $this->actingAs(User::factory()->create())
            ->from('/admin')
            ->post('/admin/tenant/switch', ['directory_id' => 999999])
            ->assertRedirect('/admin')
            ->assertSessionHasErrors('directory_id');

        $this->assertNull(session('current_directory_id'));
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
