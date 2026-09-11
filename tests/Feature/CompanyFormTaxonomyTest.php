<?php

namespace Tests\Feature;

use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\District;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class CompanyFormTaxonomyTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_form_includes_shared_and_current_directory_taxonomies(): void
    {
        $directory = Directory::create([
            'name' => 'Seçili Rehber',
            'slug' => 'secili-rehber',
            'domain' => 'secili.test',
            'status' => 'active',
        ]);
        $otherDirectory = Directory::create([
            'name' => 'Başka Rehber',
            'slug' => 'baska-rehber',
            'domain' => 'baska.test',
            'status' => 'active',
        ]);

        $sharedCategory = Category::create(['name' => 'Ortak', 'slug' => 'ortak', 'status' => 'active']);
        $ownCategory = Category::create([
            'name' => 'Özel', 'slug' => 'ozel', 'status' => 'active', 'directory_id' => $directory->id,
        ]);
        Category::create([
            'name' => 'Başka', 'slug' => 'baska', 'status' => 'active', 'directory_id' => $otherDirectory->id,
        ]);

        app()->instance('currentDirectory', $directory);

        $method = new ReflectionMethod(CompanyForm::class, 'scopeByDirectory');
        $ids = $method->invoke(null, Category::query())->pluck('id')->all();

        $this->assertEqualsCanonicalizing([$sharedCategory->id, $ownCategory->id], $ids);
    }

    public function test_company_form_filters_districts_by_selected_city(): void
    {
        $directory = Directory::create([
            'name' => 'Seçili Rehber',
            'slug' => 'secili-rehber',
            'domain' => 'secili.test',
            'status' => 'active',
        ]);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
        $otherCity = City::create(['name' => 'Edirne', 'slug' => 'edirne']);
        $sharedDistrict = District::create(['city_id' => $city->id, 'name' => 'Süleymanpaşa', 'slug' => 'suleymanpasa']);
        $ownDistrict = District::create([
            'city_id' => $city->id, 'name' => 'Özel İlçe', 'slug' => 'ozel-ilce', 'directory_id' => $directory->id,
        ]);
        District::create(['city_id' => $otherCity->id, 'name' => 'Merkez', 'slug' => 'merkez']);

        app()->instance('currentDirectory', $directory);

        $method = new ReflectionMethod(CompanyForm::class, 'scopeDistricts');
        $ids = $method->invoke(null, District::query(), $city->id)->pluck('id')->all();

        $this->assertEqualsCanonicalizing([$sharedDistrict->id, $ownDistrict->id], $ids);
    }
}
