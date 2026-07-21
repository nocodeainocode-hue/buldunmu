<?php

namespace Tests\Feature;

use App\Models\MembershipPlan;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackagePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_packages_page_loads(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Paket 1', 'slug' => 'paket-1', 'price' => 100, 'directory_id' => $dir->id, 'is_active' => true, 'sort_order' => 1]);

        $response = $this->get('/paketler');
        $response->assertStatus(200);
        $response->assertSee('Paket 1');
    }

    public function test_packages_page_shows_only_active(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Aktif', 'slug' => 'aktif', 'price' => 100, 'directory_id' => $dir->id, 'is_active' => true, 'sort_order' => 1]);
        MembershipPlan::create(['name' => 'Pasif', 'slug' => 'pasif', 'price' => 200, 'directory_id' => $dir->id, 'is_active' => false, 'sort_order' => 2]);

        $response = $this->get('/paketler');
        $response->assertSee('Aktif');
        $response->assertDontSee('Pasif');
    }
}
