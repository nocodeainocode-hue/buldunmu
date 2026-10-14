<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\CategoryDirectorySetting;
use App\Models\Directory;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryDirectoryVisibilityAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_admin_list_shows_and_toggles_visibility_for_the_selected_directory(): void
    {
        $directory = Directory::create(['name' => 'Kuaför Rehberi', 'slug' => 'kuaforrehberi', 'domain' => 'kuaforrehberi.com.tr', 'status' => 'active']);
        $shown = Category::create(['name' => 'Erkek Kuaför', 'slug' => 'erkek-kuafor', 'status' => 'active']);
        $hidden = Category::create(['name' => 'Takı ve Aksesuarları', 'slug' => 'taki-ve-aksesuarlari', 'status' => 'active']);
        CategoryDirectorySetting::create(['category_id' => $hidden->id, 'directory_id' => $directory->id, 'is_visible' => false]);

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        app()->instance('currentDirectory', $directory);

        Livewire::test(ListCategories::class)
            ->assertTableColumnFormattedStateSet('status', 'Bu rehberde gizli', $hidden)
            ->assertTableColumnFormattedStateSet('status', 'Aktif', $shown)
            ->filterTable('visible_here', true)
            ->assertCanSeeTableRecords([$shown])
            ->assertCanNotSeeTableRecords([$hidden])
            ->filterTable('visible_here', false)
            ->assertCanSeeTableRecords([$hidden])
            ->assertCanNotSeeTableRecords([$shown])
            ->filterTable('visible_here', null)
            ->callTableAction('toggle_here', $hidden)
            ->callTableAction('toggle_here', $shown);

        $this->assertTrue(CategoryDirectorySetting::where(['category_id' => $hidden->id, 'directory_id' => $directory->id])->value('is_visible'));
        $this->assertFalse(CategoryDirectorySetting::where(['category_id' => $shown->id, 'directory_id' => $directory->id])->value('is_visible'));
    }
}
