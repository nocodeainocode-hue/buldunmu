<?php

namespace Tests\Unit;

use App\Models\MembershipPlan;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_plan(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $plan = MembershipPlan::create([
            'name' => 'Temel Paket', 'slug' => 'temel-paket',
            'price' => 299.90, 'currency' => 'TRY',
            'billing_period' => 'monthly', 'features' => [
                ['title' => 'Firma Kaydı', 'description' => '1 firma', 'icon' => 'building'],
                ['title' => 'Öne Çıkarma', 'description' => 'Ana sayfada', 'icon' => 'star'],
            ],
            'is_active' => true, 'sort_order' => 1, 'directory_id' => $dir->id,
        ]);

        $this->assertDatabaseHas('membership_plans', ['name' => 'Temel Paket', 'price' => 299.90]);
        $this->assertEquals('TRY', $plan->currency);
        $this->assertIsArray($plan->features);
        $this->assertCount(2, $plan->features);
        $this->assertEquals('Firma Kaydı', $plan->features[0]['title']);
    }

    public function test_scope_active(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        MembershipPlan::create(['name' => 'Aktif', 'slug' => 'aktif', 'price' => 100, 'directory_id' => $dir->id, 'is_active' => true, 'sort_order' => 1]);
        MembershipPlan::create(['name' => 'Pasif', 'slug' => 'pasif', 'price' => 200, 'directory_id' => $dir->id, 'is_active' => false, 'sort_order' => 2]);

        $this->assertEquals(2, MembershipPlan::count());
        $this->assertEquals(1, MembershipPlan::active()->count());
    }

    public function test_belongs_to_directory(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $plan = MembershipPlan::create(['name' => 'Paket', 'slug' => 'paket', 'price' => 100, 'directory_id' => $dir->id, 'sort_order' => 1]);

        $this->assertNotNull($plan->directory);
        $this->assertEquals('Test', $plan->directory->name);
    }

    public function test_features_cast_to_array(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $plan = MembershipPlan::create([
            'name' => 'Paket', 'slug' => 'paket', 'price' => 100, 'directory_id' => $dir->id,
            'features' => ['title' => 'Test', 'description' => 'Desc'],
        ]);

        $fresh = MembershipPlan::find($plan->id);
        $this->assertIsArray($fresh->features);
    }
}
