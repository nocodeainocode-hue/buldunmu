<?php

namespace Tests\Feature;

use App\Filament\Resources\OwnerAccounts\Pages\ListOwnerAccounts;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOwner;
use App\Models\Directory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OwnerAccountAdminTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
    }

    private function registeredOwner(string $company, string $email, string $phone, string $status = 'pending', ?Directory $directory = null): CompanyOwner
    {
        $directory ??= $this->directory;
        $category = Category::firstOrCreate(['slug' => 'dis-klinigi'], ['name' => 'Diş Kliniği', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'tekirdag'], ['name' => 'Tekirdağ']);
        $user = User::factory()->create(['name' => 'Ayşe Yılmaz', 'email' => $email]);
        $user->forceFill(['utm_source' => 'google', 'utm_campaign' => 'yaz', 'signup_directory_id' => $directory->id])->save();
        $record = Company::create([
            'name' => $company, 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => $status, 'phone' => $phone, 'whatsapp' => $phone,
        ]);

        return CompanyOwner::create([
            'company_id' => $record->id, 'user_id' => $user->id, 'directory_id' => $directory->id,
            'role' => 'owner', 'status' => 'active',
        ]);
    }

    public function test_admin_sees_registered_owners_with_firm_contact_and_source(): void
    {
        $this->registeredOwner('Ayşe Diş Kliniği', 'ayse@example.test', '0532 123 45 67');
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(ListOwnerAccounts::class)
            ->assertSee('Ayşe Diş Kliniği')
            ->assertSee('ayse@example.test')
            ->assertSee('0532 123 45 67')
            ->assertSee('Ayşe Yılmaz')
            ->assertSee('Buldun mu?')
            ->assertSee('Onay bekliyor')
            ->assertSee('google / yaz');
    }

    public function test_owners_of_every_directory_are_listed_even_when_one_is_selected_in_the_session(): void
    {
        $other = Directory::create(['name' => 'Diğer Rehber', 'slug' => 'diger', 'domain' => 'diger.test', 'status' => 'active']);
        $this->registeredOwner('Birinci Firma', 'bir@example.test', '0532 111 22 33');
        $this->registeredOwner('İkinci Firma', 'iki@example.test', '0533 234 56 78', 'pending', $other);
        $this->actingAs(User::factory()->create(['is_admin' => true]))->withSession(['current_directory_id' => $this->directory->id]);
        app()->instance('currentDirectory', $this->directory);

        Livewire::test(ListOwnerAccounts::class)->assertSee('Birinci Firma')->assertSee('İkinci Firma');
    }

    public function test_search_filters_and_pending_tab_work(): void
    {
        $this->registeredOwner('Bekleyen Klinik', 'bekleyen@example.test', '0532 111 22 33', 'pending');
        $this->registeredOwner('Yayındaki Klinik', 'yayinda@example.test', '0533 234 56 78', 'active');
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(ListOwnerAccounts::class)
            ->searchTable('yayinda@example.test')
            ->assertSee('Yayındaki Klinik')
            ->assertDontSee('Bekleyen Klinik');

        Livewire::test(ListOwnerAccounts::class)
            ->set('activeTab', 'pending')
            ->assertSee('Bekleyen Klinik')
            ->assertDontSee('Yayındaki Klinik');

        Livewire::test(ListOwnerAccounts::class)
            ->searchTable('0533 234')
            ->assertSee('Yayındaki Klinik')
            ->assertDontSee('Bekleyen Klinik');
    }

    public function test_page_is_admin_only_and_details_open(): void
    {
        $owner = $this->registeredOwner('Ayşe Diş Kliniği', 'ayse@example.test', '0532 123 45 67');

        $this->actingAs(User::factory()->create(['is_admin' => false]))->get('/admin/owner-accounts')->assertForbidden();

        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin/owner-accounts')->assertOk()->assertSee('Kayıtlı');

        Livewire::test(ListOwnerAccounts::class)
            ->callTableAction('details', $owner)
            ->assertSee('ayse@example.test');
    }
}
