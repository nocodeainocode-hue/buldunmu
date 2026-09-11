<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\District;
use App\Models\ListingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ListingRequestDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_listing_request_is_assigned_to_the_current_directory(): void
    {
        $directory = Directory::create([
            'name' => 'Tekirdağ Firma Rehberi',
            'slug' => 'tekirdag-firma-rehberi',
            'domain' => 'tekirdag.test',
            'status' => 'active',
        ]);
        $category = Category::create([
            'directory_id' => $directory->id,
            'name' => 'Su Arıtma',
            'slug' => 'su-aritma',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
        ]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'tekirdag.test'])
            ->post('http://tekirdag.test/firma-ekle', [
                'company_name' => 'Örnek Su Arıtma',
                'phone' => '0282 000 00 00',
                'category_id' => $category->id,
                'city_id' => $city->id,
            ]);

        $response->assertRedirect('http://tekirdag.test/firma-ekle');
        $this->assertDatabaseHas('listing_requests', [
            'company_name' => 'Örnek Su Arıtma',
            'directory_id' => $directory->id,
            'status' => 'new',
        ]);
    }

    public function test_frontend_form_contains_shared_districts_and_requires_catalog_selection(): void
    {
        $directory = Directory::create([
            'name' => 'Tekirdağ Rehberi',
            'slug' => 'tekirdag-rehberi',
            'domain' => 'tekirdag.test',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
            'directory_id' => null,
        ]);
        District::create([
            'name' => 'Çorlu',
            'slug' => 'corlu',
            'city_id' => $city->id,
            'directory_id' => null,
        ]);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://tekirdag.test/firma-ekle')
            ->assertOk()
            ->assertSee('\u00c7orlu', false);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->from('http://tekirdag.test/firma-ekle')
            ->post('http://tekirdag.test/firma-ekle', ['company_name' => 'Eksik Firma'])
            ->assertSessionHasErrors(['category_id', 'city_id']);
    }

    public function test_request_directory_is_carried_to_the_approved_company(): void
    {
        $directory = Directory::create([
            'name' => 'Yalnız Bu Rehber',
            'slug' => 'yalniz-bu-rehber',
            'domain' => 'yalniz.test',
            'status' => 'active',
        ]);
        $category = Category::create([
            'directory_id' => $directory->id,
            'name' => 'Tesisat',
            'slug' => 'tesisat',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
        ]);

        $request = ListingRequest::create([
            'company_name' => 'Tek Rehber Firması',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'new',
        ]);

        $company = $request->approveToCompany();

        $this->assertSame($directory->id, $request->directory_id);
        $this->assertSame($directory->id, $company->directory_id);
        $this->assertSame('approved', $request->fresh()->status);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Tek Rehber Firması',
            'directory_id' => null,
        ]);
    }

    public function test_legacy_request_cannot_be_approved_without_selecting_a_directory(): void
    {
        $request = ListingRequest::create([
            'company_name' => 'Kaynağı Belirsiz Firma',
            'status' => 'new',
        ]);

        $this->expectException(InvalidArgumentException::class);

        $request->approveToCompany();
    }

    public function test_request_cannot_use_a_category_from_another_directory(): void
    {
        $sourceDirectory = Directory::create([
            'name' => 'Kaynak Rehber',
            'slug' => 'kaynak-rehber',
            'domain' => 'kaynak.test',
            'status' => 'active',
        ]);
        $otherDirectory = Directory::create([
            'name' => 'Diğer Rehber',
            'slug' => 'diger-rehber',
            'domain' => 'diger.test',
            'status' => 'active',
        ]);
        $otherCategory = Category::create([
            'directory_id' => $otherDirectory->id,
            'name' => 'Başka Kategori',
            'slug' => 'baska-kategori',
            'status' => 'active',
        ]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => $sourceDirectory->domain])
            ->from('http://kaynak.test/firma-ekle')
            ->post('http://kaynak.test/firma-ekle', [
                'company_name' => 'Yanlış Kategorili Firma',
                'category_id' => $otherCategory->id,
            ]);

        $response
            ->assertRedirect('http://kaynak.test/firma-ekle')
            ->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('listing_requests', [
            'company_name' => 'Yanlış Kategorili Firma',
        ]);
    }

    public function test_null_directory_companies_are_not_shared_between_sites(): void
    {
        $directory = Directory::create([
            'name' => 'İzole Rehber',
            'slug' => 'izole-rehber',
            'domain' => 'izole.test',
            'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Ortak Kategori',
            'slug' => 'ortak-kategori',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
        ]);

        Company::create([
            'name' => 'Eski Global Firma',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
        ]);

        app()->instance('currentDirectory', $directory);

        $this->assertFalse(Company::where('name', 'Eski Global Firma')->exists());
    }

    public function test_www_host_resolves_the_root_domain_directory(): void
    {
        $directory = Directory::create([
            'name' => 'WWW Rehberi',
            'slug' => 'www-rehberi',
            'domain' => 'www-test.test',
            'status' => 'active',
        ]);

        $response = $this
            ->withServerVariables(['HTTP_HOST' => 'www.www-test.test'])
            ->get('http://www.www-test.test/firma-ekle');

        $response->assertOk();
        $this->assertTrue(app()->bound('currentDirectory'));
        $this->assertSame($directory->id, app('currentDirectory')->id);
    }
}
