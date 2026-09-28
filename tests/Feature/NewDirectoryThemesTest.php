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

    public function test_ilan_board_has_its_own_inner_pages_and_keeps_business_features(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Borsa Testi', 'slug' => 'borsa-testi', 'domain' => $host,
            'status' => 'active', 'template' => 'ilan-board',
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

        // Ana sayfa + firma detayı (ortak td-* parçaları ilan temasına bağlanır)
        $this->get('/')->assertOk()->assertSee('theme-ilan-board', false)
            ->assertSee('Özgün Atölye')->assertSee(route('search'), false);

        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ib-detail', false)
            ->assertSee('ib-detail__head', false)->assertSee('Kayıt ', false)
            ->assertSee('Ahşap Masa')->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        foreach ([
            // [imza, ham mı (class/URL) yoksa kaçırılmış metin mi]
            '/firmalar' => ['ib-filters', false],
            '/ara?q=Özgün' => ['ib-items', false],
            '/kategori/mobilya' => ['ib-catnav', false],
            '/sehir/izmir' => ['ib-filters', false],
            '/is-ilanlari' => ['Personel', false],
            '/is-ilanlari/'.$job->slug => ['Görev tanımı', false],
            '/blog' => ['ib-items', false],
            '/blog/'.$post->slug => ['ib-prose', false],
            '/iletisim' => ['ib-form', false],
            '/hakkimizda' => ['ib-prose', false],
            '/gizlilik-politikasi' => ['ib-box', false],
            '/kullanim-sartlari' => ['ib-prose', false],
            '/paketler' => ['ib-plan', false],
        ] as $url => [$expected, $escaped]) {
            $this->get($url)->assertOk()->assertSee('ib-band', false)->assertSee($expected, $escaped);
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

    /**
     * Elegant Premium: lüks editöryal tasarım sistemi (el-*). Masaüstü tema; iç sayfalar
     * kendi band + bileşenlerini render eder, detay ortak td-* parçalarını el-detay ile override eder.
     */
    public function test_elegant_theme_renders_bespoke_inner_pages_and_detail(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Zarif Test', 'slug' => 'zarif-test', 'domain' => $host,
            'status' => 'active', 'template' => 'elegant',
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

        // Ana sayfa: editöryal tasarım sistemi + lüks serif font (registry → Google Fonts)
        $this->get('/')->assertOk()->assertSee('theme-elegant', false)
            ->assertSee('el-stats', false)->assertSee('el-cta', false)
            ->assertSee('Cormorant')->assertSee('Özgün Atölye')
            ->assertSee(route('search'), false);

        // Firma detayı: el-detail kabuğu + ortak td-* bölümleri + index,follow
        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('el-detail', false)
            ->assertSee('Kayıt ', false)->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false)
            ->assertSee('index,follow', false);

        foreach ([
            '/firmalar' => 'el-filters',
            '/ara?q=Özgün' => 'el-items',
            '/kategori/mobilya' => 'el-band',
            '/sehir/izmir' => 'el-band',
            '/is-ilanlari' => 'el-filters',
            '/is-ilanlari/'.$job->slug => 'el-meta',
            '/blog' => 'el-band',
            '/blog/'.$post->slug => 'el-prose',
            '/iletisim' => 'el-form',
            '/hakkimizda' => 'el-prose',
            '/gizlilik-politikasi' => 'el-box',
            '/kullanim-sartlari' => 'el-prose',
            '/paketler' => 'el-plan',
        ] as $url => $signature) {
            $this->get($url)->assertOk()->assertSee('el-band', false)->assertSee($signature, false);
        }
    }

    /**
     * Mobil Uygulama: özel .ap cihaz kabuğu. PHONE_SHELL'e eklendi → isPhoneShell() true;
     * iç sayfalar masaüstü @else dalına düşmez, .ap-shell-content + ap-tabbar içinde render olur.
     */
    public function test_mobile_app_theme_wraps_inner_pages_in_app_shell(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Cep Uygulama', 'slug' => 'cep-uygulama', 'domain' => $host,
            'status' => 'active', 'template' => 'mobile-app',
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

        // Kabuk artık PHONE_SHELL içinde tanınır
        $this->assertTrue(\App\View\Helpers\ThemeHelper::isPhoneShell($directory));

        // Ana sayfa: kendi .ap cihazını render eder (appbar + arama + chip + feed + tabbar)
        $this->get('/')->assertOk()->assertSee('theme-mobile-app', false)
            ->assertSee('ap-appbar', false)->assertSee('ap-tabbar', false)
            ->assertSee('ap-search', false)->assertSee('Özgün Atölye')
            ->assertSee(route('search'), false);

        // Firma detayı: .ap cihazı içinde, ortak td-* bölümleri .ap-detail ile override
        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('ap-detail__cover', false)
            ->assertSee('ap-tabbar', false)->assertSee('Ahşap Masa')
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        // İç sayfalar: .ap-shell-content + ap-tabbar içinde (masaüstü @else değil)
        foreach ([
            '/firmalar' => 'ap-chips',
            '/ara?q=Özgün' => 'ap-list',
            '/kategori/mobilya' => 'ap-chips',
            '/sehir/izmir' => 'ap-chips',
            '/is-ilanlari' => 'ap-list',
            '/is-ilanlari/'.$job->slug => 'ap-facts',
            '/blog' => 'ap-feed',
            '/blog/'.$post->slug => 'ap-prose',
            '/iletisim' => 'ap-form',
            '/hakkimizda' => 'ap-prose',
            '/gizlilik-politikasi' => 'ap-prose',
            '/kullanim-sartlari' => 'ap-prose',
            '/paketler' => 'ap-plan',
        ] as $url => $signature) {
            $this->get($url)->assertOk()
                ->assertSee('ap-shell-content', false)
                ->assertSee('ap-tabbar', false)
                ->assertSee($signature, false);
        }

        $this->get('/firma-ekle')->assertRedirect(route('owner.register'));
    }
}
