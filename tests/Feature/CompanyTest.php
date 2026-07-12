<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    protected Directory $dir;
    protected Category $category;
    protected City $city;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = Directory::create(['name'=>'Test','slug'=>'test','domain'=>'test.local','status'=>'active']);
        $this->category = Category::create(['name'=>'Restoran','slug'=>'restoran','status'=>'active','directory_id'=>$this->dir->id]);
        $this->city = City::create(['name'=>'İstanbul','slug'=>'istanbul','directory_id'=>$this->dir->id]);
    }

    public function test_company_detail_page_shows_info(): void
    {
        $company = Company::create([
            'name' => 'Test Firma',
            'slug' => 'test-firma',
            'category_id' => $this->category->id,
            'city_id' => $this->city->id,
            'phone' => '+905551112233',
            'status' => 'active',
            'directory_id' => $this->dir->id,
        ]);

        $response = $this->get('/firma/test-firma');
        $response->assertStatus(200);
        $response->assertSee('Test Firma');
        $response->assertSee('+905551112233');
    }

    public function test_review_can_be_submitted(): void
    {
        $company = Company::create([
            'name' => 'Test Firma', 'slug' => 'test-firma',
            'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'directory_id' => $this->dir->id,
        ]);

        $response = $this->post('/firma/test-firma/yorum', [
            'name' => 'Müşteri',
            'rating' => 5,
            'comment' => 'Harika bir firma, kesinlikle tavsiye ederim!',
        ]);

        $response->assertSessionHas('review_success');
        $this->assertDatabaseHas('company_reviews', [
            'company_id' => $company->id,
            'name' => 'Müşteri',
            'status' => 'pending',
        ]);
    }

    public function test_review_requires_name(): void
    {
        $company = Company::create([
            'name' => 'Test Firma', 'slug' => 'test-firma2',
            'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'directory_id' => $this->dir->id,
        ]);

        $response = $this->post('/firma/test-firma2/yorum', [
            'rating' => 3,
            'comment' => 'Yorum',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_google_maps_embed_appears(): void
    {
        $company = Company::create([
            'name' => 'Haritalı', 'slug' => 'haritali',
            'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'directory_id' => $this->dir->id,
            'google_maps_url' => 'https://www.google.com/maps/embed?pb=test',
        ]);

        $response = $this->get('/firma/haritali');
        $response->assertStatus(200);
        $response->assertSee('google.com/maps/embed');
    }

    public function test_maps_section_hidden_when_empty(): void
    {
        $company = Company::create([
            'name' => 'Haritasiz', 'slug' => 'haritasiz',
            'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'directory_id' => $this->dir->id,
        ]);

        $response = $this->get('/firma/haritasiz');
        $response->assertStatus(200);
        $response->assertDontSee('google.com/maps/embed');
    }

    public function test_empty_city_is_noindex_and_excluded_from_sitemap(): void
    {
        $this->get('/sehir/istanbul')
            ->assertStatus(200)
            ->assertSee('content="noindex,follow,max-image-preview:large"', false);

        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertDontSee('/sehir/istanbul');
    }

    public function test_city_with_active_company_is_indexable_and_included_in_sitemap(): void
    {
        Company::create([
            'name' => 'Aktif Firma',
            'category_id' => $this->category->id,
            'city_id' => $this->city->id,
            'status' => 'active',
            'directory_id' => $this->dir->id,
        ]);

        $this->get('/sehir/istanbul')
            ->assertStatus(200)
            ->assertSee('content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1"', false);

        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertSee('/sehir/istanbul');
    }
}
