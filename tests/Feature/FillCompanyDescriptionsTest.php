<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\District;
use App\Services\CompanyDescriptionGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FillCompanyDescriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_locative_suffix_follows_vowel_harmony_and_consonant_rules(): void
    {
        $g = new CompanyDescriptionGenerator;

        $this->assertSame("Bursa'da", $g->locative('Bursa'));
        $this->assertSame("Nilüfer'de", $g->locative('Nilüfer'));
        $this->assertSame("Kars'ta", $g->locative('Kars'));
        $this->assertSame("Beşiktaş'ta", $g->locative('Beşiktaş'));
        $this->assertSame("İzmir'de", $g->locative('İzmir'));
        $this->assertSame("Kırıkhan'da", $g->locative('Kırıkhan'));
        $this->assertSame("Gebze'de", $g->locative('Gebze'));
        $this->assertSame("Kadıköy'de", $g->locative('Kadıköy'));
    }

    private function seedCompanies(int $directoryCount = 3): array
    {
        $city = City::create(['name' => 'Bursa', 'slug' => 'bursa']);
        $district = District::create(['name' => 'Nilüfer', 'slug' => 'nilufer', 'city_id' => $city->id]);
        $category = Category::create(['name' => 'Otomotiv', 'slug' => 'otomotiv', 'status' => 'active']);
        $companies = [];

        foreach (range(1, $directoryCount) as $i) {
            $directory = Directory::create(['name' => "Rehber {$i}", 'slug' => "rehber-{$i}", 'domain' => "rehber{$i}.example", 'status' => 'active']);
            $companies[] = Company::create([
                'name' => 'Örnek <Oto> & Servis', 'directory_id' => $directory->id, 'category_id' => $category->id,
                'city_id' => $city->id, 'district_id' => $district->id, 'status' => 'pending',
                'phone' => '02245022276', 'address' => 'Odunluk Mah. 6/A, 16110 Nilüfer/Bursa',
            ]);
        }

        return $companies;
    }

    public function test_it_writes_directory_specific_descriptions_without_touching_filled_ones(): void
    {
        $companies = $this->seedCompanies(6);
        $companies[0]->update(['short_description' => 'Elle yazılmış açıklama', 'description' => '<p>Elle yazılmış metin</p>']);

        $this->artisan('companies:fill-descriptions')->assertSuccessful();

        $fresh = Company::withoutGlobalScope('directory')->orderBy('id')->get();

        $this->assertSame('Elle yazılmış açıklama', $fresh[0]->short_description);
        $this->assertSame('<p>Elle yazılmış metin</p>', $fresh[0]->description);

        foreach ($fresh->skip(1) as $company) {
            $this->assertNotEmpty($company->short_description);
            $this->assertGreaterThan(200, mb_strlen(strip_tags($company->description)));
            $this->assertStringNotContainsString('{', $company->short_description.$company->description);
            $this->assertStringContainsString('0224 502 22 76', strip_tags($company->description));
            // HTML kaçışı: ad içindeki <Oto> etiket gibi basılmamalı.
            $this->assertStringNotContainsString('<Oto>', $company->description);
        }

        $this->assertGreaterThan(
            2,
            $fresh->skip(1)->pluck('description')->unique()->count(),
            'Aynı firma farklı rehberlerde farklı metin almalı.',
        );
    }

    public function test_dry_run_does_not_write_and_force_overwrites(): void
    {
        $this->seedCompanies(2);

        $this->artisan('companies:fill-descriptions --dry-run')->assertSuccessful();
        $this->assertSame(0, Company::withoutGlobalScope('directory')->whereNotNull('short_description')->count());

        $this->artisan('companies:fill-descriptions')->assertSuccessful();
        Company::withoutGlobalScope('directory')->update(['short_description' => 'x']);

        $this->artisan('companies:fill-descriptions')->assertSuccessful();
        $this->assertSame(2, Company::withoutGlobalScope('directory')->where('short_description', 'x')->count());

        $this->artisan('companies:fill-descriptions --force')->assertSuccessful();
        $this->assertSame(0, Company::withoutGlobalScope('directory')->where('short_description', 'x')->count());
    }
}
