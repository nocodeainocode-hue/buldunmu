<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOwner;
use App\Models\Directory;
use App\Models\PageView;
use App\Models\User;
use App\Support\HtmlSanitizer;
use App\Support\ModelCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private function fixture(): array
    {
        $directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
        $company = Company::create([
            'name' => 'Ayşe Diş Kliniği', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'phone' => '02820000001',
        ]);

        return [$directory, $category, $city, $company];
    }

    private function host(Directory $directory): self
    {
        return $this->withServerVariables(['HTTP_HOST' => $directory->domain]);
    }

    public function test_sanitizer_removes_scripts_handlers_and_unsafe_links_but_keeps_formatting(): void
    {
        $dirty = '<p onclick="steal()">Merhaba <strong>dünya</strong></p><script>alert(1)</script>'
            .'<a href="javascript:alert(1)">kötü</a><a href=" JaVa&#x09;script:alert(2)">kötü2</a>'
            .'<a href="https://example.com/x" target="_blank" onmouseover="x()">iyi</a>'
            .'<iframe src="https://evil.test"></iframe><img src=x onerror=alert(3)><ul><li>Madde</li></ul>';

        $clean = HtmlSanitizer::clean($dirty);

        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsString('alert(', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onmouseover', $clean);
        $this->assertStringNotContainsString('javascript', strtolower($clean));
        $this->assertStringNotContainsString('iframe', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringContainsString('<strong>dünya</strong>', $clean);
        $this->assertStringContainsString('<li>Madde</li>', $clean);
        $this->assertStringContainsString('href="https://example.com/x"', $clean);
        $this->assertStringContainsString('rel="nofollow noopener ugc"', $clean);
    }

    public function test_sanitizer_escapes_plain_text_without_double_encoding(): void
    {
        $this->assertSame('Ali &amp; Veli &lt;3', HtmlSanitizer::clean('Ali &amp; Veli <3'));
        $this->assertSame('', HtmlSanitizer::clean(null));
    }

    public function test_company_description_is_sanitized_when_saved_and_when_shown(): void
    {
        [$directory, , , $company] = $this->fixture();

        $company->update(['description' => '<p>Güvenli metin</p><script>alert("xss")</script>']);

        $this->assertStringNotContainsString('script', $company->fresh()->description);

        // Veritabanına doğrudan yazılmış eski kirli veri de gösterimde temizlenir.
        \DB::table('companies')->where('id', $company->id)->update(['description' => '<p>Eski</p><script>alert("eski")</script>']);

        $this->host($directory)->get('http://buldunmu.test/firma/'.$company->slug)
            ->assertOk()
            ->assertSee('Eski')
            ->assertDontSee('alert("eski")', false);
    }

    public function test_owner_cannot_inject_script_through_the_company_form(): void
    {
        [$directory, , , $company] = $this->fixture();
        $user = User::factory()->create();
        CompanyOwner::create(['company_id' => $company->id, 'user_id' => $user->id, 'directory_id' => $directory->id, 'role' => 'owner', 'status' => 'active']);

        $this->actingAs($user)->host($directory)
            ->put('http://buldunmu.test/panel/firma/'.$company->slug, ['description' => '<p>Selam</p><script>alert(1)</script>']);

        $this->assertStringNotContainsString('<script', (string) $company->fresh()->description);
    }

    public function test_campaign_csv_and_tenant_switch_require_an_admin(): void
    {
        $owner = User::factory()->create(['is_admin' => false]);

        $this->actingAs($owner)->get('/admin/campaigns/1/export-csv')->assertForbidden();
        $this->actingAs($owner)->post('/admin/tenant/switch', ['directory_id' => null])->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post('/admin/tenant/switch', ['directory_id' => null])->assertRedirect();
    }

    public function test_owner_login_is_locked_after_repeated_failures(): void
    {
        [$directory] = $this->fixture();
        User::factory()->create(['email' => 'ayse@example.test', 'password' => bcrypt('dogru-parola')]);

        for ($i = 0; $i < 5; $i++) {
            $this->host($directory)->post('http://buldunmu.test/panel/giris', ['email' => 'ayse@example.test', 'password' => 'yanlis'])
                ->assertSessionHasErrors('email');
        }

        $response = $this->host($directory)->post('http://buldunmu.test/panel/giris', ['email' => 'ayse@example.test', 'password' => 'dogru-parola']);
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Çok fazla deneme', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_honeypot_blocks_bot_reviews_silently(): void
    {
        [$directory, , , $company] = $this->fixture();
        $payload = ['name' => 'Bot', 'rating' => 5, 'comment' => 'Bu bir otomatik yorumdur, lütfen.'];

        $this->host($directory)->post('http://buldunmu.test/firma/'.$company->slug.'/yorum', $payload + ['fax_no' => 'http://spam.test'])
            ->assertRedirect();
        $this->assertDatabaseCount('company_reviews', 0);

        $this->host($directory)->post('http://buldunmu.test/firma/'.$company->slug.'/yorum', $payload);
        $this->assertDatabaseCount('company_reviews', 1);
    }

    public function test_review_form_is_rate_limited(): void
    {
        [$directory, , , $company] = $this->fixture();
        $payload = ['name' => 'Ali', 'rating' => 5, 'comment' => 'Gayet iyi bir hizmet aldık, teşekkürler.'];

        for ($i = 0; $i < 6; $i++) {
            $this->host($directory)->post('http://buldunmu.test/firma/'.$company->slug.'/yorum', $payload)->assertRedirect();
        }

        $this->host($directory)->post('http://buldunmu.test/firma/'.$company->slug.'/yorum', $payload)->assertStatus(429);
    }

    public function test_pages_carry_security_headers_and_the_honeypot_field(): void
    {
        [$directory, , , $company] = $this->fixture();

        $response = $this->host($directory)->get('http://buldunmu.test/firma/'.$company->slug)->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertSee('name="fax_no"', false);

        $this->host($directory)->get('http://buldunmu.test/firma-kayit')->assertSee('name="fax_no"', false);
    }

    public function test_bots_are_not_counted_but_visitors_are_recorded_after_the_response(): void
    {
        [$directory, , , $company] = $this->fixture();

        $this->host($directory)->withHeader('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1)')
            ->get('http://buldunmu.test/firma/'.$company->slug)->assertOk();
        $this->assertSame(0, PageView::count());
        $this->assertSame(0, (int) $company->fresh()->view_count);

        $this->host($directory)->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64) Chrome/130.0 Safari/537.36')
            ->get('http://buldunmu.test/firma/'.$company->slug)->assertOk();
        $this->assertSame(1, PageView::count());
        $this->assertSame($company->id, PageView::first()->company_id);
        $this->assertSame(1, (int) $company->fresh()->view_count);
    }

    public function test_prune_command_removes_only_old_page_views(): void
    {
        PageView::create(['path' => '/eski', 'ip_hash' => 'a', 'created_at' => now()->subDays(400)]);
        PageView::create(['path' => '/yeni', 'ip_hash' => 'b', 'created_at' => now()->subDays(5)]);

        $this->artisan('pageviews:prune --days=180')->assertSuccessful();

        $this->assertSame(['/yeni'], PageView::pluck('path')->all());
    }

    public function test_model_cache_stores_models_and_is_invalidated_by_bump(): void
    {
        config(['performance.model_cache' => true]);
        [$directory] = $this->fixture();
        $calls = 0;
        $load = function () use (&$calls, $directory) {
            $calls++;

            return Directory::find($directory->id);
        };

        $first = ModelCache::remember('directories', 'host.x', 60, $load);
        $second = ModelCache::remember('directories', 'host.x', 60, $load);

        $this->assertInstanceOf(Directory::class, $second);
        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, $calls);

        ModelCache::bump('directories');
        ModelCache::remember('directories', 'host.x', 60, $load);
        $this->assertSame(2, $calls);
    }

    public function test_home_page_and_settings_work_with_the_cache_enabled_and_refresh_after_changes(): void
    {
        config(['performance.model_cache' => true]);
        [$directory, $category, $city, $company] = $this->fixture();

        $this->host($directory)->get('http://buldunmu.test/')->assertOk()->assertSee('Ayşe Diş Kliniği');
        $this->host($directory)->get('http://buldunmu.test/')->assertOk()->assertSee('Ayşe Diş Kliniği');

        Company::create([
            'name' => 'Yepyeni Klinik', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'phone' => '02820000002',
        ]);

        $this->host($directory)->get('http://buldunmu.test/')->assertOk()->assertSee('Yepyeni Klinik');
    }
}
