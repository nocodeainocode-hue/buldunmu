<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Services\CompanyDescriptionGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairCompanyEntitiesTest extends TestCase
{
    use RefreshDatabase;

    private function company(Directory $directory, array $attributes): Company
    {
        $city = City::firstOrCreate(['slug' => 'bursa'], ['name' => 'Bursa']);
        $category = Category::firstOrCreate(['slug' => 'mobilya'], ['name' => 'Mobilya', 'status' => 'active']);

        return Company::create($attributes + [
            'directory_id' => $directory->id, 'category_id' => $category->id, 'city_id' => $city->id,
            'status' => 'active', 'phone' => '02245022276',
        ]);
    }

    public function test_it_decodes_entities_fixes_the_slug_and_refreshes_generated_text(): void
    {
        $directory = Directory::create(['name' => 'Rehber', 'slug' => 'rehber', 'domain' => 'rehber.example', 'status' => 'active', 'slug_pattern' => '{name}']);
        $broken = $this->company($directory, [
            'name' => 'Kamsan Sandalye Bursa İnegöl Mobilya &#8211; Yenice Fabrika',
            'address' => 'Merkez &amp; Sanayi Sitesi, 16400 İnegöl/Bursa',
        ]);

        // Eski hâl: bozuk ad ile üretilmiş açıklama ve "8211" kalıntılı slug.
        $text = app(CompanyDescriptionGenerator::class)->generate($broken->fresh()->load(['category', 'city']), $directory);
        $broken->forceFill(['short_description' => $text['short'], 'description' => $text['html']])->saveQuietly();
        $this->assertStringContainsString('8211', $broken->fresh()->slug);

        $handEdited = $this->company($directory, [
            'name' => 'Doğal &amp; Lezzetli Fırın', 'short_description' => 'Elle yazılmış kısa metin',
            'description' => '<p>Elle yazılmış&nbsp;metin</p>',
        ]);
        $clean = $this->company($directory, ['name' => 'Temiz Firma', 'address' => 'Merkez']);

        $this->artisan('companies:repair-entities')->assertSuccessful();

        $broken->refresh();
        $this->assertSame('Kamsan Sandalye Bursa İnegöl Mobilya – Yenice Fabrika', $broken->name);
        $this->assertSame('Merkez & Sanayi Sitesi, 16400 İnegöl/Bursa', $broken->address);
        $this->assertStringNotContainsString('8211', $broken->slug);
        $this->assertStringNotContainsString('&#', $broken->short_description.$broken->description);
        $this->assertStringContainsString('Yenice Fabrika', $broken->short_description.strip_tags($broken->description));

        // Elle yazılan açıklama ve alan korunur; yalnızca ad/adres düzelir.
        $handEdited->refresh();
        $this->assertSame('Doğal & Lezzetli Fırın', $handEdited->name);
        $this->assertSame('Elle yazılmış kısa metin', $handEdited->short_description);
        $this->assertSame('<p>Elle yazılmış&nbsp;metin</p>', $handEdited->description);

        $this->assertSame('Temiz Firma', $clean->fresh()->name);
        $this->assertSame($clean->slug, $clean->fresh()->slug);
    }

    public function test_dry_run_writes_nothing_and_slug_collisions_get_a_suffix(): void
    {
        $directory = Directory::create(['name' => 'Rehber', 'slug' => 'rehber', 'domain' => 'rehber.example', 'status' => 'active', 'slug_pattern' => '{name}']);
        $a = $this->company($directory, ['name' => 'Usta &#8211; Servis']);
        $existing = $this->company($directory, ['name' => 'Usta – Servis']);
        $before = [$a->fresh()->name, $a->fresh()->slug];

        $this->artisan('companies:repair-entities --dry-run')->assertSuccessful();
        $this->assertSame($before, [$a->fresh()->name, $a->fresh()->slug]);

        $this->artisan('companies:repair-entities')->assertSuccessful();
        $this->assertSame('Usta – Servis', $a->fresh()->name);
        $this->assertNotSame($existing->slug, $a->fresh()->slug, 'Aynı rehberde slug çakışmamalı');
        $this->assertStringStartsWith('usta-servis', $a->fresh()->slug);
    }
}
