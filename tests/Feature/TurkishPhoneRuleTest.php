<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use App\Rules\TurkishPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TurkishPhoneRuleTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('validNumbers')]
    public function test_accepts_real_turkish_numbers(string $number): void
    {
        $this->assertTrue(TurkishPhone::isValid($number), $number);
    }

    public static function validNumbers(): array
    {
        return [
            'cep, boşluklu' => ['0532 123 45 67'],
            'cep, bitişik' => ['05321234567'],
            'cep, sıfırsız' => ['532 123 45 67'],
            'uluslararası' => ['+90 532 123 45 67'],
            'uluslararası 0090' => ['0090 532 123 45 67'],
            'sabit, parantezli' => ['(0282) 123 45 67'],
            'sabit, tireli' => ['0212-555-12-34'],
            'bölge kodu 3xx' => ['0312 123 45 67'],
            'bölge kodu 4xx' => ['0462 123 45 67'],
            '850 hattı' => ['0850 123 45 67'],
            '444 hizmet numarası' => ['444 12 34'],
            'boş (zorunluluk ayrı)' => [''],
        ];
    }

    #[DataProvider('invalidNumbers')]
    public function test_rejects_garbage(string $number): void
    {
        $this->assertFalse(TurkishPhone::isValid($number), $number);
    }

    public static function invalidNumbers(): array
    {
        return [
            'harfler' => ['adasdasd'],
            'harf karışık' => ['0532 abc 45 67'],
            'çok kısa' => ['12345'],
            'eksik hane' => ['0532 123 45 6'],
            'fazla hane' => ['0532 123 45 678'],
            'yanlış alan kodu 1xx' => ['0132 123 45 67'],
            'yanlış alan kodu 6xx' => ['0632 123 45 67'],
            'alan kodsuz 7 hane' => ['123 45 67'],
            'tekrarlı' => ['0555 555 55 55'],
            'iki rakam tekrarı' => ['0505 050 50 50'],
            'e-posta' => ['ali@firma.com'],
            'sembol' => ['0532-123-45-67!'],
        ];
    }

    private function fixture(): array
    {
        $directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);

        return [$directory, $category, $city];
    }

    public function test_registration_form_rejects_a_garbage_phone_and_saves_nothing(): void
    {
        [$directory, $category, $city] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ayşe Yılmaz', 'email' => 'ayse@example.test',
                'password' => 'guvenli-parola', 'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ayşe Diş Kliniği', 'category_id' => $category->id,
                'city_id' => $city->id, 'phone' => 'adasdasd',
            ])
            ->assertSessionHasErrors(['phone' => TurkishPhone::MESSAGE]);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('companies', 0);
    }

    public function test_registration_form_also_checks_whatsapp(): void
    {
        [$directory, $category, $city] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ayşe Yılmaz', 'email' => 'ayse@example.test',
                'password' => 'guvenli-parola', 'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ayşe Diş Kliniği', 'category_id' => $category->id,
                'city_id' => $city->id, 'phone' => '0282 000 00 01', 'whatsapp' => 'yok',
            ])
            ->assertSessionHasErrors('whatsapp');
    }

    public function test_step_one_lead_is_not_saved_for_a_garbage_phone(): void
    {
        [$directory, $category, $city] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->postJson('http://buldunmu.test/firma-kayit/on-basvuru', [
                'company_name' => 'Ayşe Diş Kliniği', 'category_id' => $category->id, 'city_id' => $city->id, 'phone' => 'adasdasd',
            ])->assertStatus(422);

        $this->assertSame(0, ListingRequest::withoutGlobalScope('directory')->count());
    }

    public function test_add_company_form_rejects_a_garbage_phone(): void
    {
        [$directory, $category, $city] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://buldunmu.test/firma-ekle', [
                'company_name' => 'Yeni Firma', 'category_id' => $category->id, 'city_id' => $city->id,
                'contact_name' => 'Ali', 'phone' => 'asdf', 'captcha' => '1',
            ])
            ->assertSessionHasErrors('phone');

        $this->assertSame(0, ListingRequest::withoutGlobalScope('directory')->count());
    }

    public function test_contact_form_rejects_a_garbage_phone_but_allows_it_empty(): void
    {
        [$directory] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->withSession(['captcha_result' => 4])
            ->post('http://buldunmu.test/iletisim', ['name' => 'Ali', 'message' => 'Merhaba', 'phone' => 'abc', 'captcha' => '4'])
            ->assertSessionHasErrors('phone');

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->withSession(['captcha_result' => 4])
            ->post('http://buldunmu.test/iletisim', ['name' => 'Ali', 'message' => 'Merhaba', 'captcha' => '4'])
            ->assertSessionHasNoErrors();
    }

    public function test_registration_page_warns_in_the_browser_before_submitting(): void
    {
        [$directory] = $this->fixture();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://buldunmu.test/firma-kayit')
            ->assertOk()
            ->assertSee('Geçerli bir telefon numarası girin, örn. 0532 123 45 67', false)
            ->assertSee('pattern="[0-9+()\s.\-]{10,20}"', false);
    }

    public function test_owner_profile_update_rejects_a_garbage_phone(): void
    {
        [$directory, $category, $city] = $this->fixture();
        $user = User::factory()->create();
        $company = \App\Models\Company::create([
            'name' => 'Ayşe Diş Kliniği', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'phone' => '02820000001',
        ]);
        \App\Models\CompanyOwner::create(['company_id' => $company->id, 'user_id' => $user->id, 'directory_id' => $directory->id, 'role' => 'owner', 'status' => 'active']);

        $this->actingAs($user)->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->put('http://buldunmu.test/panel/firma/'.$company->slug, ['phone' => 'adasdasd'])
            ->assertSessionHasErrors('phone');

        $this->assertSame('02820000001', $company->fresh()->phone);

        $this->actingAs($user)->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->put('http://buldunmu.test/panel/firma/'.$company->slug, ['phone' => '0282 000 00 02'])
            ->assertSessionHasNoErrors();
    }
}
