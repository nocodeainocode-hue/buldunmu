<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerRegistrationTest extends TestCase
{
    use RefreshDatabase;

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

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://buldunmu.test/panel/kampanyalar')
            ->assertOk()
            ->assertSee('100 Firma Rehberinde Yayın Projesi')
            ->assertSee('4.900 TL')
            ->assertSee('Ayşe Diş Kliniği');
    }
}
