<?php

namespace Tests\Unit;

use App\Models\PageView;
use App\Models\Directory;
use App\Models\Company;
use App\Models\Category;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pageview(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $pv = PageView::create([
            'path' => '/firma/test-firma', 'ip_hash' => 'abc123',
            'user_agent_summary' => 'Chrome/120', 'directory_id' => $dir->id,
        ]);
        $this->assertDatabaseHas('page_views', ['path' => '/firma/test-firma']);
    }

    public function test_pageview_belongs_to_directory(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $pv = PageView::create(['path' => '/', 'ip_hash' => 'hash', 'directory_id' => $dir->id]);
        $this->assertEquals('Test', $pv->directory->name);
    }

    public function test_pageview_belongs_to_company(): void
    {
        $dir = Directory::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'test.local', 'status' => 'active']);
        $cat = Category::create(['name' => 'Cat', 'slug' => 'cat', 'status' => 'active', 'directory_id' => $dir->id]);
        $city = City::create(['name' => 'City', 'slug' => 'city', 'directory_id' => $dir->id]);
        $company = Company::create(['name' => 'Firma', 'slug' => 'firma', 'category_id' => $cat->id, 'city_id' => $city->id, 'status' => 'active', 'directory_id' => $dir->id]);
        $pv = PageView::create(['path' => '/firma/firma', 'ip_hash' => 'hash', 'company_id' => $company->id, 'directory_id' => $dir->id]);

        $this->assertEquals('Firma', $pv->company->name);
    }
}
