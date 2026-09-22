<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_uses_the_short_two_step_flow(): void
    {
        $directory = Directory::create([
            'name' => 'Buldun mu?',
            'slug' => 'buldun-mu',
            'domain' => 'buldunmu.test',
            'status' => 'active',
        ]);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://buldunmu.test/firma-kayit')
            ->assertOk()
            ->assertSee('1. Firma bilgileri')
            ->assertSee('2. Panel hesabı')
            ->assertSee('Kredi kartı gerekmez. Kayıt ücretsizdir.')
            ->assertSee('Bilgileriniz kontrolünüzde');
    }

    public function test_registration_creates_a_pending_company_owned_only_in_the_current_directory(): void
    {
        $directory = Directory::create([
            'name' => 'Buldun mu?',
            'slug' => 'buldun-mu',
            'domain' => 'buldunmu.test',
            'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);

        $response = $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ayşe Yılmaz',
                'email' => 'ayse@example.test',
                'password' => 'guvenli-parola',
                'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ayşe Diş Kliniği',
                'category_id' => $category->id,
                'city_id' => $city->id,
                'phone' => '0282 000 00 00',
            ]);

        $response->assertRedirect('http://buldunmu.test/panel');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'ayse@example.test', 'is_admin' => false]);
        $this->assertDatabaseHas('companies', [
            'name' => 'Ayşe Diş Kliniği',
            'directory_id' => $directory->id,
            'status' => 'pending',
        ]);

        $company = Company::withoutGlobalScope('directory')->where('name', 'Ayşe Diş Kliniği')->firstOrFail();
        $user = User::where('email', 'ayse@example.test')->firstOrFail();

        $this->assertDatabaseHas('company_owners', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'role' => 'owner',
        ]);
        $this->assertDatabaseHas('listing_requests', [
            'company_name' => 'Ayşe Diş Kliniği',
            'claim_company_id' => $company->id,
            'directory_id' => $directory->id,
            'source' => 'owner_registration',
            'status' => 'new',
        ]);

        $approval = ListingRequest::query()->latest('id')->firstOrFail();
        $approvedCompany = $approval->approveToCompany();

        $this->assertSame($company->id, $approvedCompany->id);
        $this->assertSame('active', $approvedCompany->fresh()->status);
        $this->assertSame('approved', $approval->fresh()->status);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://buldunmu.test/panel')
            ->assertOk()
            ->assertSee('Rakiplerinizden sıyrılın.')
            ->assertSee('4.900 TL');

        $this->assertNotNull($user->fresh()->campaign_popup_seen_at);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://buldunmu.test/panel/kampanyalar')
            ->assertOk()
            ->assertSee('100 Firma Rehberinde Yayın Projesi')
            ->assertSee('4.900 TL')
            ->assertSee('Ayşe Diş Kliniği');
    }
}
