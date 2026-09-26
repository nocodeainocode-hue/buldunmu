<?php

namespace Tests\Feature;

use App\Filament\Resources\JobPostings\Pages\CreateJobPosting;
use App\Filament\Resources\CompanyOfferings\Pages\CreateCompanyOffering;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOffering;
use App\Models\CompanyOwner;
use App\Models\Directory;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyContentTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;
    private Company $company;
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Test Rehberi', 'slug' => 'test-rehberi',
            'domain' => 'icerik.test', 'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Teknoloji', 'slug' => 'teknoloji', 'status' => 'active']);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $this->company = Company::create([
            'name' => 'Örnek Firma', 'directory_id' => $this->directory->id,
            'category_id' => $category->id, 'city_id' => $city->id,
            'status' => 'active', 'is_premium' => true,
        ]);
        $this->owner = User::factory()->create();
        CompanyOwner::create([
            'company_id' => $this->company->id,
            'user_id' => $this->owner->id,
            'directory_id' => $this->directory->id,
            'role' => 'owner', 'status' => 'active',
        ]);
    }

    public function test_premium_owner_can_publish_a_product_on_the_company_page(): void
    {
        $this->actingAs($this->owner)
            ->post($this->url('/panel/firma/'.$this->company->slug.'/vitrin'), [
                'type' => 'product', 'name' => 'Akıllı Termostat',
                'description' => 'Enerji yönetimi için akıllı ürün.',
                'price' => '1499.90', 'status' => 'active', 'sort_order' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('company_offerings', [
            'company_id' => $this->company->id,
            'directory_id' => $this->directory->id,
            'type' => 'product', 'name' => 'Akıllı Termostat',
        ]);
        $this->get($this->url('/firma/'.$this->company->slug))
            ->assertOk()
            ->assertSee('Akıllı Termostat');
    }

    public function test_free_and_expired_companies_cannot_publish_owner_vitrine(): void
    {
        $this->company->update(['is_premium' => false]);
        $this->actingAs($this->owner)
            ->post($this->url('/panel/firma/'.$this->company->slug.'/vitrin'), [
                'type' => 'service', 'name' => 'Kurulum', 'status' => 'active',
            ])
            ->assertForbidden();

        $this->company->update(['is_premium' => true, 'premium_until' => now()->subDay()]);
        $offering = CompanyOffering::create([
            'company_id' => $this->company->id, 'directory_id' => $this->directory->id,
            'type' => 'service', 'name' => 'Özel Kurulum', 'status' => 'active',
        ]);
        $this->get($this->url('/firma/'.$this->company->slug))
            ->assertOk()
            ->assertDontSee($offering->name);
    }

    public function test_premium_owner_job_appears_in_both_job_list_and_company_page_until_expiry(): void
    {
        $this->actingAs($this->owner)
            ->post($this->url('/panel/firma/'.$this->company->slug.'/ilanlar'), [
                'title' => 'Satış Uzmanı',
                'description' => 'Müşterilerle görüşecek deneyimli ekip arkadaşı arıyoruz.',
                'employment_type' => 'full_time',
                'location' => 'İstanbul',
                'apply_email' => 'ik@example.test',
                'status' => 'published',
                'expires_at' => now()->addDays(30)->format('Y-m-d'),
            ])
            ->assertRedirect();

        $job = JobPosting::firstOrFail();
        $this->assertSame('satis-uzmani-'.$job->id, $job->slug);
        $this->get($this->url('/is-ilanlari'))->assertOk()->assertSee('Satış Uzmanı');
        $this->get($this->url('/is-ilanlari/'.$job->slug))->assertOk()->assertSee('ik@example.test');
        $this->get($this->url('/firma/'.$this->company->slug))->assertOk()->assertSee('Satış Uzmanı');
        $this->get($this->url('/sitemap.xml'))->assertSee($job->slug);

        $job->update(['expires_at' => now()->subMinute()]);
        $this->get($this->url('/is-ilanlari'))->assertDontSee('Satış Uzmanı');
        $this->get($this->url('/is-ilanlari/'.$job->slug))->assertNotFound();
        $this->get($this->url('/sitemap.xml'))->assertDontSee($job->slug);
    }

    public function test_admin_published_job_for_free_firm_is_visible_but_owner_cannot_add_another(): void
    {
        $this->company->update(['is_premium' => false]);
        $job = JobPosting::create([
            'company_id' => $this->company->id,
            'directory_id' => $this->directory->id,
            'title' => 'Depo Görevlisi',
            'description' => 'Depo operasyonları için çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time',
            'apply_email' => 'ik@example.test',
            'status' => 'published',
            'published_at' => now(),
            'admin_published' => true,
        ]);

        $this->get($this->url('/is-ilanlari'))->assertOk()->assertSee($job->title);
        $this->get($this->url('/firma/'.$this->company->slug))->assertOk()->assertSee($job->title);
        $this->actingAs($this->owner)
            ->post($this->url('/panel/firma/'.$this->company->slug.'/ilanlar'), [
                'title' => 'İkinci İlan', 'description' => 'Yeni iş tanımı',
                'employment_type' => 'full_time', 'apply_email' => 'ik@example.test',
                'status' => 'published',
            ])
            ->assertForbidden();
    }

    public function test_owner_cannot_modify_another_companys_content(): void
    {
        $other = Company::create([
            'name' => 'Diğer Firma', 'directory_id' => $this->directory->id,
            'category_id' => $this->company->category_id, 'city_id' => $this->company->city_id,
            'status' => 'active', 'is_premium' => true,
        ]);
        $offering = CompanyOffering::create([
            'company_id' => $other->id, 'directory_id' => $this->directory->id,
            'type' => 'product', 'name' => 'Başkasının Ürünü', 'status' => 'active',
        ]);

        $this->actingAs($this->owner)
            ->put($this->url('/panel/firma/'.$other->slug.'/vitrin/'.$offering->id), [
                'type' => 'product', 'name' => 'Değiştirildi', 'status' => 'active',
            ])
            ->assertForbidden();

        $this->assertSame('Başkasının Ürünü', $offering->fresh()->name);
    }

    public function test_admin_manages_selected_directory_content_from_management_center(): void
    {
        $this->company->update(['is_premium' => false]);
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->withSession(['current_directory_id' => $this->directory->id])
            ->get($this->url('/admin/directory-management-center'))
            ->assertOk()
            ->assertSee('Ürün ve hizmetler')
            ->assertSee('İş ilanları');

        $this->get($this->url('/admin/company-offerings/create'))->assertOk();
        $this->get($this->url('/admin/job-postings/create'))->assertOk();

        app()->instance('currentDirectory', $this->directory);
        Livewire::test(CreateJobPosting::class)
            ->fillForm([
                'company_id' => $this->company->id,
                'title' => 'Yönetimden Oluşturulan İlan',
                'description' => 'Admin tarafından ücretsiz firma için yayımlanan ilan.',
                'employment_type' => 'full_time',
                'apply_email' => 'ik@example.test',
                'status' => 'published',
                'expires_at' => now()->addDays(30),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $job = JobPosting::where('title', 'Yönetimden Oluşturulan İlan')->firstOrFail();
        $this->assertTrue($job->admin_published);
        $this->assertSame($this->directory->id, $job->directory_id);
        $this->get($this->url('/is-ilanlari'))->assertSee($job->title);
    }

    public function test_admin_can_prepare_offering_for_any_firm_and_premium_controls_visibility(): void
    {
        $this->company->update(['is_premium' => false]);
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->withSession(['current_directory_id' => $this->directory->id]);
        app()->instance('currentDirectory', $this->directory);

        Livewire::test(CreateCompanyOffering::class)
            ->fillForm([
                'company_id' => $this->company->id,
                'type' => 'service',
                'name' => 'Kurumsal Bakım',
                'description' => 'Bakım hizmeti',
                'status' => 'active',
                'sort_order' => 0,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('company_offerings', [
            'company_id' => $this->company->id,
            'directory_id' => $this->directory->id,
            'name' => 'Kurumsal Bakım',
        ]);
        $this->get($this->url('/firma/'.$this->company->slug))->assertDontSee('Kurumsal Bakım');
        $this->company->update(['is_premium' => true]);
        $this->get($this->url('/firma/'.$this->company->slug))->assertSee('Kurumsal Bakım');
    }

    public function test_other_directory_cannot_see_jobs_or_offerings(): void
    {
        $otherDirectory = Directory::create([
            'name' => 'Başka Rehber', 'slug' => 'baska-rehber',
            'domain' => 'baska.test', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $this->company->id, 'directory_id' => $this->directory->id,
            'title' => 'Yalnız Bu Rehberde', 'description' => 'Bir rehberin ilanı.',
            'employment_type' => 'full_time', 'apply_email' => 'ik@example.test',
            'status' => 'published', 'published_at' => now(),
        ]);
        $this->get('http://'.$otherDirectory->domain.'/is-ilanlari')
            ->assertOk()
            ->assertDontSee($job->title);
        $this->get('http://'.$otherDirectory->domain.'/is-ilanlari/'.$job->slug)
            ->assertNotFound();
    }

    private function url(string $path): string
    {
        return 'http://'.$this->directory->domain.$path;
    }
}
