<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOffering;
use App\Models\Directory;
use App\Models\JobPosting;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewDirectoryThemesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cinematic_atlas_has_its_own_public_pages_and_keeps_business_features(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Sahne Rehberi', 'slug' => 'sahne-rehberi', 'domain' => $host,
            'status' => 'active', 'template' => 'cinematic-atlas',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true,
            'phone' => '0232 111 22 33', 'description' => str_repeat('Özgün mobilya ve tasarım hizmeti sunuyoruz. ', 3),
        ]);
        CompanyOffering::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'type' => 'product', 'name' => 'Ahşap Masa', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Ekibimize çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(), 'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);

        $this->get('/')->assertOk()->assertSee('theme-cinematic-atlas', false)
            ->assertSee('Her işletmenin')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee('Mobilya Ustası')->assertSee(route('search'), false);
        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('cinema-detail', false)
            ->assertSee('Ahşap Masa')->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        foreach ([
            '/firmalar' => 'Gösterimdeki firmalar',
            '/ara?q=Özgün' => 'Arama sonuçları',
            '/kategori/mobilya' => 'Mobilya seçkisi',
            '/sehir/izmir' => 'İzmir gösterimi',
            '/is-ilanlari' => 'Güncel iş ilanları',
            '/is-ilanlari/'.$job->slug => 'Pozisyon hakkında',
            '/blog' => 'Yayın seçkisi',
            '/blog/'.$post->slug => 'Ahşap mobilya seçerken',
            '/firma-kayit' => 'Müşteriler firmanızı kolayca bulsun',
            '/iletisim' => 'İletişim',
            '/hakkimizda' => 'Sahne arkası / Bilgi',
            '/gizlilik-politikasi' => 'Gizlilik Politikası',
            '/kullanim-sartlari' => 'Kullanım Şartları',
            '/paketler' => 'Sahnedeki yerinizi seçin',
        ] as $url => $expected) {
            $this->get($url)->assertOk()->assertSee('cinema-shell-content', false)->assertSee($expected);
        }
        $this->get('/firma-ekle')->assertRedirect(route('owner.register'));
    }

    public function test_departure_board_has_its_own_public_pages_and_keeps_business_features(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Peron Rehberi', 'slug' => 'peron-rehberi', 'domain' => $host,
            'status' => 'active', 'template' => 'departure-board',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true, 'is_verified' => true,
            'phone' => '0232 111 22 33', 'description' => str_repeat('Özgün mobilya ve tasarım hizmeti sunuyoruz. ', 3),
        ]);
        CompanyOffering::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'type' => 'product', 'name' => 'Ahşap Masa', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Ekibimize çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(), 'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);

        $this->get('/')->assertOk()->assertSee('theme-departure-board', false)
            ->assertSee('Şehirde')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee('Mobilya Ustası')->assertSee(route('search'), false);
        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('dep-detail', false)
            ->assertSee('dep-ticketbar', false)->assertSee('Bilet ', false)->assertSee('Ahşap Masa')->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        foreach ([
            '/firmalar' => 'Kalkıştaki firmalar',
            '/ara?q=Özgün' => 'Arama sonuçları',
            '/kategori/mobilya' => 'Mobilya seferleri',
            '/sehir/izmir' => 'İzmir kalkış listesi',
            '/is-ilanlari' => 'Güncel ilanlar',
            '/is-ilanlari/'.$job->slug => 'Görev tanımı',
            '/blog' => 'Yayın seçkisi',
            '/blog/'.$post->slug => 'Ahşap mobilya seçerken',
            '/firma-kayit' => 'Müşteriler firmanızı kolayca bulsun',
            '/iletisim' => 'İletişim peronu',
            '/hakkimizda' => 'Servis bürosu / Bilgi',
            '/gizlilik-politikasi' => 'Servis bürosu / Bilgi',
            '/kullanim-sartlari' => 'Servis bürosu / Bilgi',
            '/paketler' => 'Hangi sınıftan yolculuk?',
        ] as $url => $expected) {
            $this->get($url)->assertOk()->assertSee('dep-shell-content', false)->assertSee($expected);
        }
        $this->get('/firma-ekle')->assertRedirect(route('owner.register'));
    }

    /** Cep temaları için ortak veri seti. */
    private function seedPhoneTheme(string $template, string $slug, string $name): array
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => $name, 'slug' => $slug, 'domain' => $host,
            'status' => 'active', 'template' => $template,
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true, 'is_verified' => true,
            'phone' => '0232 111 22 33', 'whatsapp' => '905321112233',
            'website' => 'https://ozgun-atolye.example', 'address' => '1443/2 Sokak No 7, Bornova',
            'description' => str_repeat('Özgün mobilya ve tasarım hizmeti sunuyoruz. ', 3),
        ]);
        CompanyOffering::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'type' => 'product', 'name' => 'Ahşap Masa', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Ekibimize çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(), 'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);

        return compact('company', 'job', 'post');
    }

    public function test_story_reels_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('story-reels', 'cep-akisi', 'Cep Akışı');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-story-reels', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-rings', false)
            ->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')->assertSee('Mobilya Ustası')
            ->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('ph-tabbar', false)->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    public function test_pocket_stories_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('pocket-stories', 'cepte-hikayeler', 'Cepte Hikâyeler');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-pocket-stories', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-grid', false)
            ->assertSee('Cebindeki şehir rehberi')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('Yol tarifi')->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    public function test_swipe_cards_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('swipe-cards', 'kart-destesi', 'Kart Destesi');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-swipe-cards', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-deck', false)
            ->assertSee('Kaydır, seç, ara.')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('Telefon et')->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    public function test_pull_drawer_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('pull-drawer', 'cekmece-arama', 'Çekmece Arama');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-pull-drawer', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-pull', false)
            ->assertSee('Ne aramıştın')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee('Mobilya Ustası')->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('Hemen ara')->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    public function test_radar_scope_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('radar-scope', 'radar-ekrani', 'Radar Ekranı');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-radar-scope', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-dial', false)->assertSee('ph-blips', false)
            ->assertSee('Tarama sürüyor')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee('Mobilya Ustası')->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('Hedef kilidi')->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    public function test_index_rally_keeps_mobile_shell_on_every_public_page(): void
    {
        $data = $this->seedPhoneTheme('index-rally', 'sehir-rampasi', 'Şehir Rampası');
        $company = $data['company'];

        $this->get('/')->assertOk()->assertSee('theme-index-rally', false)
            ->assertSee('ph-tabbar', false)->assertSee('ph-rally', false)->assertSee('ph-letter', false)
            ->assertSee('Sıraya gir')->assertSee('Özgün Atölye')->assertSee('Ahşap Masa')
            ->assertSee('Mobilya Ustası')->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ph-detail', false)
            ->assertSee('Hemen ara')->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        $this->assertPhoneShellPages($data);
    }

    /** Üç cep temasında da alt sayfalar cihaz çerçevesi içinde mobil kalır. */
    private function assertPhoneShellPages(array $data): void
    {
        $pages = [
            '/firmalar' => 'Kayıtlı firmalar',
            '/ara?q=Özgün' => 'Arama sonuçları',
            '/kategori/mobilya' => 'Mobilya seçkisi',
            '/sehir/izmir' => 'İzmir firmaları',
            '/is-ilanlari' => 'Güncel ilanlar',
            '/is-ilanlari/'.$data['job']->slug => 'Görev tanımı',
            '/blog' => 'Yayın seçkisi',
            '/blog/'.$data['post']->slug => 'Ahşap mobilya seçerken',
            '/firma-kayit' => 'Müşteriler firmanızı kolayca bulsun',
            '/iletisim' => 'Mesaj formu',
            '/hakkimizda' => 'Bilgi · Servis',
            '/gizlilik-politikasi' => 'Bilgi · Servis',
            '/kullanim-sartlari' => 'Bilgi · Servis',
            '/paketler' => 'Hangi paket sana uygun?',
        ];

        foreach ($pages as $url => $expected) {
            $this->get($url)->assertOk()
                ->assertSee('ph-shell-content', false)
                ->assertSee('ph-tabbar', false)
                ->assertSee($expected);
        }

        $this->get('/firma-ekle')->assertRedirect(route('owner.register'));
    }

    /**
     * Sinyal İstasyonu: masaüstü marka teması, özel kabuk yerine varsayılan header/footer kullanır.
     * İç sayfalar .sig / .sig-band tasarım sistemiyle kendi düzenini render eder.
     */
    public function test_signal_station_has_its_own_inner_pages_and_keeps_business_features(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Sinyal Testi', 'slug' => 'sinyal-testi', 'domain' => $host,
            'status' => 'active', 'template' => 'signal-station',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true, 'is_verified' => true,
            'phone' => '0232 111 22 33', 'description' => str_repeat('Özgün mobilya ve tasarım hizmeti sunuyoruz. ', 3),
        ]);
        CompanyOffering::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'type' => 'product', 'name' => 'Ahşap Masa', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Ekibimize çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(), 'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);

        // Ana sayfa + firma detayı (ortak td-* parçaları sinyal temasına bağlanır)
        $this->get('/')->assertOk()->assertSee('theme-signal-station', false)
            ->assertSee('Özgün Atölye')->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('sig-detail', false)
            ->assertSee('sig-band', false)->assertSee('Sinyal ', false)
            ->assertSee('Ahşap Masa')->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        foreach ([
            // [imza, ham mı (class/URL) yoksa kaçırılmış metin mi]
            '/firmalar' => ['sig-filters', false],
            '/ara?q=Özgün' => ['Arama', false],
            '/kategori/mobilya' => ['sig-filters', false],
            '/sehir/izmir' => ['sig-filters', false],
            '/is-ilanlari' => ['Personel', false],
            '/is-ilanlari/'.$job->slug => ['Pozisyon', false],
            '/blog' => ['Yayın seçkisi', true],
            '/blog/'.$post->slug => ['sig-prose', false],
            '/iletisim' => ['sig-form', false],
            '/hakkimizda' => ['sig-prose', false],
            '/gizlilik-politikasi' => ['sig-panel', false],
            '/kullanim-sartlari' => ['Bilgi', false],
            '/paketler' => ['sig-plan', false],
        ] as $url => [$expected, $escaped]) {
            $this->get($url)->assertOk()->assertSee('sig-band', false)->assertSee($expected, $escaped);
        }
    }

    public function test_three_new_homepages_render_search_and_company_links(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Keşif Testi',
            'slug' => 'kesif-testi',
            'domain' => $host,
            'status' => 'active',
            'template' => 'signal-station',
        ]);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Tesisat', 'slug' => 'tesisat', 'status' => 'active']);
        Company::create([
            'name' => 'Örnek Tesisat',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'directory_id' => $directory->id,
            'status' => 'active',
            'is_premium' => true,
        ]);

        foreach (['signal-station', 'paper-trail', 'orbit-directory'] as $template) {
            $directory->update(['template' => $template]);

            $response = $this->get('/');

            $response->assertOk();
            $response->assertSee('Örnek Tesisat');
            $response->assertSee(route('search'), false);
            $response->assertSee('theme-'.$template, false);
        }
    }

    public function test_two_utopian_homepages_render_search_categories_and_company_links(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Ütopya Testi', 'slug' => 'utopya-testi', 'domain' => $host,
            'status' => 'active', 'template' => 'sky-archipelago',
        ]);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Tasarım', 'slug' => 'tasarim', 'status' => 'active']);
        Company::create([
            'name' => 'Yeni Dünya Tasarım', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active',
        ]);

        foreach (['sky-archipelago', 'luminous-garden'] as $template) {
            $directory->update(['template' => $template]);

            $this->get('/')
                ->assertOk()
                ->assertSee('Yeni Dünya Tasarım')
                ->assertSee(route('search'), false)
                ->assertSee(route('categories.show', $category->slug), false)
                ->assertSee('theme-'.$template, false);
        }
    }

    public function test_marginal_homepages_show_directory_content_and_new_premium_features(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Yeni Pano', 'slug' => 'yeni-pano', 'domain' => $host,
            'status' => 'active', 'template' => 'classifieds-board',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'category_id' => $category->id,
            'city_id' => $city->id, 'directory_id' => $directory->id,
            'status' => 'active', 'is_premium' => true, 'phone' => '0232 111 22 33',
            'description' => str_repeat('Özgün mobilya üretimi ve özel tasarım hizmetleri sunuyoruz. ', 2),
        ]);
        CompanyOffering::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'type' => 'product', 'name' => 'Ahşap Masa', 'status' => 'active',
        ]);
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Atölye ekibine katılın.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(),
            'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);

        foreach (['classifieds-board', 'acid-poster'] as $template) {
            $directory->update(['template' => $template]);

            $this->get('/')
                ->assertOk()
                ->assertSee('theme-'.$template, false)
                ->assertSee('Özgün Atölye')
                ->assertSee('Ahşap Masa')
                ->assertSee('Mobilya Ustası')
                ->assertSee(route('search'), false)
                ->assertSee(route('categories.show', $category->slug), false);

            $this->get('/firma/'.$company->slug)
                ->assertOk()
                ->assertSee($template === 'classifieds-board' ? 'cb-detail' : 'ap-detail', false)
                ->assertSee('Özgün Atölye')
                ->assertSee('Ahşap Masa')
                ->assertSee('Mobilya Ustası')
                ->assertSee('id="urunler-hizmetler"', false)
                ->assertSee('id="yorumlar"', false)
                ->assertSee(route('companies.reviews.store', $company->slug), false)
                ->assertSee('index,follow,max-image-preview:large', false);
        }

        $directory->update(['template' => 'classifieds-board']);

        foreach ([
            '/firmalar' => 'Firma kayıtları',
            '/ara?q=Özgün' => 'Arama sonuçları',
            '/kategori/'.$category->slug => $category->name.' kayıtları',
            '/sehir/'.$city->slug => $city->name.' kayıtları',
            '/is-ilanlari' => 'Güncel ilanlar',
            '/is-ilanlari/'.$job->slug => 'Pozisyon hakkında',
            '/blog' => 'Yayın akışı',
            '/blog/'.$post->slug => 'Ahşap mobilya seçerken',
            '/firma-kayit' => 'Müşteriler firmanızı kolayca bulsun',
            '/iletisim' => 'İletişim',
        ] as $url => $expected) {
            $this->get($url)
                ->assertOk()
                ->assertSee('board-shell-content', false)
                ->assertSee($expected);
        }

        $this->get('/kategori/'.$category->slug.'?city=ankara')
            ->assertOk()
            ->assertDontSee('class="bp-row-title" href="'.route('companies.show', $company->slug).'"', false);
    }
}
