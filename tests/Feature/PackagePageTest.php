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
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'packages.test', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Paket 1', 'slug' => 'paket-1', 'price' => 100, 'directory_id' => $dir->id, 'is_active' => true, 'sort_order' => 1]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'packages.test'])
            ->get('http://packages.test/paketler');
        $response->assertStatus(200);
        $response->assertSee('Paket 1');
    }

    public function test_packages_page_shows_only_active(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'packages.test', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Aktif', 'slug' => 'aktif', 'price' => 100, 'directory_id' => $dir->id, 'is_active' => true, 'sort_order' => 1]);
        MembershipPlan::create(['name' => 'Pasif', 'slug' => 'pasif', 'price' => 200, 'directory_id' => $dir->id, 'is_active' => false, 'sort_order' => 2]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'packages.test'])
            ->get('http://packages.test/paketler');
        $response->assertSee('Aktif');
        $response->assertDontSee('Pasif');
    }

    public function test_global_plans_are_used_when_directory_has_no_active_specific_plan(): void
    {
        $dir = Directory::create(['name' => 'Fallback', 'slug' => 'fallback', 'domain' => 'fallback.test', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Genel Premium', 'slug' => 'genel-premium', 'price' => 500, 'directory_id' => null, 'is_active' => true]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'fallback.test'])
            ->get('http://fallback.test/paketler');

        $response->assertOk()->assertSee('Genel Premium');
    }

    public function test_active_directory_plans_replace_global_plans(): void
    {
        $dir = Directory::create(['name' => 'Özel', 'slug' => 'ozel', 'domain' => 'ozel.test', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Genel Premium', 'slug' => 'genel-premium', 'price' => 500, 'directory_id' => null, 'is_active' => true]);
        MembershipPlan::create(['name' => 'Rehbere Özel', 'slug' => 'rehbere-ozel', 'price' => 750, 'directory_id' => $dir->id, 'is_active' => true]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'ozel.test'])
            ->get('http://ozel.test/paketler');

        $response
            ->assertOk()
            ->assertSee('Rehbere Özel')
            ->assertDontSee('Genel Premium');
    }

    public function test_inactive_directory_plans_do_not_block_global_fallback(): void
    {
        $dir = Directory::create(['name' => 'Pasif Özel', 'slug' => 'pasif-ozel', 'domain' => 'pasif.test', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Genel Premium', 'slug' => 'genel-premium', 'price' => 500, 'directory_id' => null, 'is_active' => true]);
        MembershipPlan::create(['name' => 'Pasif Özel Paket', 'slug' => 'pasif-ozel-paket', 'price' => 750, 'directory_id' => $dir->id, 'is_active' => false]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'pasif.test'])
            ->get('http://pasif.test/paketler');

        $response
            ->assertOk()
            ->assertSee('Genel Premium')
            ->assertDontSee('Pasif Özel Paket');
    }
}
