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

    public function test_public_firma_ekle_url_redirects_to_the_account_and_company_registration_flow(): void
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
            ->assertRedirect('http://tekirdag.test/firma-kayit');
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

    public function test_profile_claim_updates_the_existing_company_without_creating_a_duplicate(): void
    {
        $directory = Directory::create([
            'name' => 'Profil Rehberi',
            'slug' => 'profil-rehberi',
            'domain' => 'profil.test',
            'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Diş Kliniği',
            'slug' => 'dis-klinigi',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'İstanbul',
            'slug' => 'istanbul',
        ]);
        $company = Company::create([
            'name' => 'Örnek Klinik',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'phone' => '0212 000 00 00',
            'status' => 'active',
        ]);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get("http://profil.test/firma/{$company->slug}/sahiplen")
            ->assertOk()
            ->assertSee('Örnek Klinik profilini sahiplenin')
            ->assertSee('name="claim_company_id"', false);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://profil.test/firma-ekle', [
                'claim_company_id' => $company->id,
                'company_name' => 'Örnek Klinik',
                'phone' => '0212 111 11 11',
                'website' => 'https://ornekklinik.test',
                'category_id' => $category->id,
                'city_id' => $city->id,
            ])
            ->assertRedirect("http://profil.test/firma/{$company->slug}/sahiplen");

        $claim = ListingRequest::query()->latest('id')->firstOrFail();

        $this->assertSame($company->id, $claim->claim_company_id);
        $updatedCompany = $claim->approveToCompany();

        $this->assertSame($company->id, $updatedCompany->id);
        $this->assertSame('0212 111 11 11', $updatedCompany->fresh()->phone);
        $this->assertSame('https://ornekklinik.test', $updatedCompany->fresh()->website);
        $this->assertSame(1, Company::withoutGlobalScope('directory')->count());
        $this->assertSame('approved', $claim->fresh()->status);
    }

    public function test_shared_company_can_be_claimed_into_a_directory_specific_profile(): void
    {
        $directory = Directory::create([
            'name' => 'Yerel Profil Rehberi',
            'slug' => 'yerel-profil-rehberi',
            'domain' => 'yerel-profil.test',
            'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Elektrikçi',
            'slug' => 'elektrikci',
            'status' => 'active',
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
        ]);
        $sharedCompany = Company::create([
            'name' => 'Ortak Elektrik',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
        ]);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get("http://yerel-profil.test/firma/{$sharedCompany->slug}/sahiplen")
            ->assertOk();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://yerel-profil.test/firma-ekle', [
                'claim_company_id' => $sharedCompany->id,
                'company_name' => 'Ortak Elektrik',
                'phone' => '0282 222 22 22',
                'category_id' => $category->id,
                'city_id' => $city->id,
            ]);

        $claim = ListingRequest::query()->latest('id')->firstOrFail();
        $directoryCompany = $claim->approveToCompany();

        $this->assertNotSame($sharedCompany->id, $directoryCompany->id);
        $this->assertSame($directory->id, $directoryCompany->directory_id);
        $this->assertDatabaseCount('companies', 2);
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

    public function test_null_directory_companies_are_visible_on_all_sites(): void
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

        $this->assertTrue(Company::where('name', 'Eski Global Firma')->exists());
    }

    public function test_directory_company_takes_priority_over_shared_company_with_the_same_slug(): void
    {
        $directory = Directory::create([
            'name' => 'Yerel Rehber',
            'slug' => 'yerel-rehber',
            'domain' => 'yerel.test',
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

        foreach ([
            ['name' => 'Genel Firma', 'directory_id' => null],
            ['name' => 'Yerel Firma', 'directory_id' => $directory->id],
        ] as $company) {
            Company::create(array_merge($company, [
                'slug' => 'ortak-firma',
                'category_id' => $category->id,
                'city_id' => $city->id,
                'status' => 'active',
            ]));
        }

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://yerel.test/firma/ortak-firma')
            ->assertOk()
            ->assertSee('Yerel Firma');
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
            ->get('http://www.www-test.test/firma-kayit');

        $response->assertOk();
        $this->assertTrue(app()->bound('currentDirectory'));
        $this->assertSame($directory->id, app('currentDirectory')->id);
    }
}
