<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\DirectoryManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_management_center_updates_only_the_selected_directory(): void
    {
        $first = Directory::create([
            'name' => 'Birinci',
            'slug' => 'birinci',
            'domain' => 'birinci.test',
            'status' => 'active',
            'theme' => ['primary' => '#111111'],
        ]);
        $second = Directory::create([
            'name' => 'İkinci',
            'slug' => 'ikinci',
            'domain' => 'ikinci.test',
            'status' => 'active',
            'theme' => ['primary' => '#222222'],
        ]);

        $service = app(DirectoryManagementService::class);
        $data = $service->formData($first);
        $data['name'] = 'Birinci Yeni';
        $data['domain'] = 'https://www.BIRINCI-YENI.test/yol';
        $data['homepage_title'] = 'Yeni Ana Sayfa';
        $data['phone'] = '555 111 22 33';
        $data['theme_primary'] = '#abcdef';

        $service->update($first, $data);

        $this->assertDatabaseHas('directories', [
            'id' => $first->id,
            'name' => 'Birinci Yeni',
            'domain' => 'birinci-yeni.test',
        ]);
        $this->assertSame('#abcdef', $first->fresh()->theme['primary']);
        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $first->id,
            'site_name' => 'Birinci Yeni',
            'homepage_title' => 'Yeni Ana Sayfa',
            'phone' => '555 111 22 33',
        ]);

        $this->assertSame('İkinci', $second->fresh()->name);
        $this->assertSame('#222222', $second->fresh()->theme['primary']);
        $this->assertDatabaseHas('site_settings', [
            'directory_id' => $second->id,
            'site_name' => 'İkinci',
            'homepage_title' => 'İkinci - Aradiginiz Firmayi Bulun',
            'phone' => null,
        ]);
    }

    public function test_form_data_combines_directory_and_its_own_site_settings(): void
    {
        $directory = Directory::create([
            'name' => 'Rehber',
            'slug' => 'rehber',
            'domain' => 'rehber.test',
            'status' => 'active',
            'theme' => ['accent' => '#ff5500'],
        ]);

        SiteSetting::withoutGlobalScope('directory')
            ->where('directory_id', $directory->id)
            ->update(['homepage_title' => 'Özel Başlık']);

        $data = app(DirectoryManagementService::class)->formData($directory);

        $this->assertSame('Rehber', $data['name']);
        $this->assertSame('Özel Başlık', $data['homepage_title']);
        $this->assertSame('#ff5500', $data['theme_accent']);
    }

    public function test_management_center_opens_for_the_selected_directory(): void
    {
        $directory = Directory::create([
            'name' => 'Seçili Rehber',
            'slug' => 'secili-rehber',
            'domain' => 'secili.test',
            'status' => 'active',
        ]);

        $this->actingAs(User::factory()->create())
            ->withSession(['current_directory_id' => $directory->id])
            ->get('/admin/directory-management-center')
            ->assertOk()
            ->assertSee('Seçili Rehber')
            ->assertSee('Tüm Değişiklikleri Kaydet');
    }

    public function test_management_center_explains_that_a_directory_must_be_selected(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/directory-management-center')
            ->assertOk()
            ->assertSee('Önce bir rehber seçin');
    }
}
