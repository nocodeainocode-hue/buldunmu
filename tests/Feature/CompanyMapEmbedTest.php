<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMapEmbedTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create(['name' => 'Harita Rehberi', 'slug' => 'harita-rehberi', 'domain' => 'harita.test', 'status' => 'active']);
    }

    private function company(array $attributes = []): Company
    {
        $category = Category::firstOrCreate(['slug' => 'temizlik'], ['name' => 'Temizlik Hizmetleri', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'istanbul'], ['name' => 'İstanbul']);

        return Company::create($attributes + [
            'name' => 'Avrupa Halı Yıkama', 'directory_id' => $this->directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'phone' => '0544 855 47 46',
        ]);
    }

    public function test_search_links_are_turned_into_an_embeddable_address_with_the_coordinates(): void
    {
        $company = $this->company(['google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=41.086243,29.003287&query_place_id=ChIJLz2uqIm3yhQREh5pPJXdT7M']);

        $this->assertSame('https://www.google.com/maps?q=41.086243,29.003287&z=16&output=embed', $company->googleMapsEmbedSrc());
    }

    public function test_long_place_links_with_3d_4d_coordinates_are_supported(): void
    {
        $company = $this->company(['google_maps_url' => 'https://www.google.com/maps/place/Avrupa+Hal%C4%B1/data=!4m7!3m6!1s0x14cab789a8ae3d2f:0xb34f!8m2!3d41.086243!4d29.003287!16s%2Fg%2F11z8zxpgmc']);

        $this->assertSame('https://www.google.com/maps?q=41.086243,29.003287&z=16&output=embed', $company->googleMapsEmbedSrc());
    }

    public function test_existing_embed_addresses_and_iframe_code_are_kept(): void
    {
        $embed = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3000!2d29.0!3d41.0';
        $this->assertSame($embed, $this->company(['google_maps_url' => $embed])->googleMapsEmbedSrc());

        $iframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!2d29.0!3d41.0" width="600"></iframe>';
        $this->assertSame('https://www.google.com/maps/embed?pb=!1m18!2d29.0!3d41.0', $this->company(['name' => 'İkinci', 'google_maps_url' => $iframe])->googleMapsEmbedSrc());
    }

    public function test_short_links_without_coordinates_fall_back_to_the_address(): void
    {
        $company = $this->company(['google_maps_url' => 'https://maps.app.goo.gl/abc123', 'address' => 'Emniyet Evleri, Bozkurt Sk. No:6/A']);
        $company->latitude = null;
        $company->longitude = null;

        $src = $company->googleMapsEmbedSrc();

        $this->assertStringStartsWith('https://www.google.com/maps?q=', $src);
        $this->assertStringEndsWith('&z=15&output=embed', $src);
        $this->assertStringContainsString(rawurlencode('Emniyet Evleri, Bozkurt Sk. No:6/A'), $src);
    }

    public function test_without_any_location_no_map_is_rendered_and_no_broken_frame_appears(): void
    {
        $company = $this->company();
        $company->latitude = null;
        $company->longitude = null;
        $company->address = null;

        $this->assertNull($company->googleMapsEmbedSrc());

        $this->withServerVariables(['HTTP_HOST' => 'harita.test'])->get('http://harita.test/firma/'.$company->slug)
            ->assertOk()
            ->assertDontSee('output=embed', false)
            ->assertDontSee('Google Maps Konumu');
    }

    public function test_company_page_frames_the_embeddable_address_not_the_search_link(): void
    {
        $company = $this->company(['google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=41.086243,29.003287&query_place_id=ChIJabc']);

        $this->withServerVariables(['HTTP_HOST' => 'harita.test'])->get('http://harita.test/firma/'.$company->slug)
            ->assertOk()
            ->assertSee('src="https://www.google.com/maps?q=41.086243,29.003287&amp;z=16&amp;output=embed"', false)
            ->assertDontSee('maps/search/?api=1', false);
    }
}
