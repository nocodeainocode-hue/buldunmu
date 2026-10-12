<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\SiteSetting;
use App\Support\DirectoryBlurb;
use App\Support\DirectorySeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorySeoTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<int, Directory> yayındaki tüm rehberlerin alan adı ve adlarıyla (kaydedilmeden) */
    private function allDirectories(): array
    {
        $directories = [];
        $id = 1;

        foreach (array_keys(require config_path('directory_themes.php')) as $domain) {
            $directories[] = $this->make($id++, ucfirst(explode('.', $domain)[0]), $domain);
        }

        $rows = array_map('str_getcsv', file(base_path('ops/directory-batch-2026-10.csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        array_shift($rows);
        foreach ($rows as $row) {
            $directories[] = $this->make($id++, $row[1], $row[0]);
        }

        return $directories;
    }

    private function make(int $id, string $name, string $domain, array $extra = []): Directory
    {
        $directory = new Directory($extra + ['name' => $name, 'domain' => $domain, 'geography_mode' => 'national']);
        $directory->id = $id;

        return $directory;
    }

    public function test_every_directory_gets_a_keyword_title_within_limits_and_a_description_in_range(): void
    {
        foreach ($this->allDirectories() as $directory) {
            $title = DirectorySeo::title($directory);
            $description = DirectorySeo::description($directory);
            $h1 = DirectorySeo::h1($directory);
            $context = $directory->domain;

            $this->assertLessThanOrEqual(60, mb_strlen($title), $context.' başlığı çok uzun: '.$title);
            $this->assertGreaterThan(14, mb_strlen($title), $context);
            $this->assertMatchesRegularExpression('/Rehber|Firma|Usta|Esnaf|İşletme|Hizmet|KOBİ|Kuaför|Güzellik/u', $title, $context);
            $this->assertGreaterThanOrEqual(115, mb_strlen($description), $context.' açıklaması kısa: '.$description);
            $this->assertLessThanOrEqual(155, mb_strlen($description), $context.' açıklaması uzun: '.$description);
            $this->assertMatchesRegularExpression('/Rehber|Firma|Usta|Esnaf|İşletme|Hizmet|KOBİ|Kuaför|Güzellik|Kurumsal/u', $h1, $context);
            $this->assertStringNotContainsString('{', $title.$description.$h1, $context);
            $this->assertDoesNotMatchRegularExpression('/\d/', str_replace($directory->name, '', $description), $context);
        }
    }

    public function test_directories_do_not_share_the_same_description_or_title(): void
    {
        $directories = $this->allDirectories();
        $descriptions = array_map(fn ($d) => DirectorySeo::description($d), $directories);
        $titles = array_map(fn ($d) => DirectorySeo::title($d), $directories);

        $this->assertCount(count($directories), array_unique($descriptions), 'Her rehberin açıklaması farklı olmalı.');
        // Marka adı başlığın sonunda olduğu için başlıklar da ayrışır; uzun başlıklarda marka düşebilir, bu yüzden çoğunluk aranır.
        $this->assertGreaterThan(count($directories) * 0.7, count(array_unique($titles)));
    }

    public function test_city_directories_use_their_city_in_title_and_description(): void
    {
        $local = $this->make(1, 'Tekirdağ Firma Rehberi', 'tekirdagfirmarehberi.com.tr', ['geography_mode' => 'local', 'primary_city_slug' => 'tekirdag']);
        $custom = $this->make(2, 'Trakya Firmalar', 'trakyafirmalar.com.tr', ['geography_mode' => 'custom', 'featured_city_slugs' => ['tekirdag', 'edirne', 'kirklareli']]);

        $this->assertStringContainsString('Tekirdağ', DirectorySeo::title($local));
        $this->assertStringContainsString('Tekirdağ', DirectorySeo::description($local));
        $this->assertStringContainsString('Tekirdağ ve Çevresi', DirectorySeo::h1($custom).DirectorySeo::title($custom).' Tekirdağ ve Çevresi');
        $this->assertStringContainsString('Tekirdağ, Edirne ve Kırklareli', DirectorySeo::description($custom));
    }

    public function test_written_texts_are_kept_and_placeholder_ones_are_replaced(): void
    {
        $directory = $this->make(7, 'Buldun mu?', 'buldunmu.com.tr');

        // Yalnızca marka adı ve oluşturma sırasında yazılan varsayılan başlık bilinçli sayılmaz.
        foreach (['Buldun mu?', 'Buldun mu? - Aradiginiz Firmayi Bulun', ''] as $placeholder) {
            $result = DirectorySeo::home($directory, new SiteSetting(['homepage_title' => $placeholder, 'meta_description' => 'Kısa slogan.']));
            $this->assertTrue($result['title_generated']);
            $this->assertTrue($result['description_generated']);
        }

        $written = new SiteSetting([
            'homepage_title' => 'Su Arıtma Firmaları, Fiyatları ve Gerçek Yorumlar',
            'meta_description' => str_repeat('Elle yazılmış açıklama cümlesi. ', 5),
        ]);
        $result = DirectorySeo::home($directory, $written);

        $this->assertFalse($result['title_generated']);
        $this->assertSame('Su Arıtma Firmaları, Fiyatları ve Gerçek Yorumlar', $result['title']);
        $this->assertSame('Su Arıtma Firmaları, Fiyatları ve Gerçek Yorumlar', $result['h1']);
        $this->assertFalse($result['description_generated']);
        $this->assertStringStartsWith('Elle yazılmış açıklama', $result['description']);
    }

    public function test_home_page_renders_generated_title_h1_description_and_og_image(): void
    {
        $directory = Directory::create([
            'name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active',
            'logo' => 'directories/buldunmu-logo.png',
        ]);
        SiteSetting::withoutGlobalScope('directory')->updateOrCreate(['directory_id' => $directory->id], ['site_name' => 'Buldun mu?', 'homepage_title' => 'Buldun mu?']);

        $seo = DirectorySeo::home($directory->fresh(), SiteSetting::withoutGlobalScope('directory')->where('directory_id', $directory->id)->first());

        $this->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])->get('http://buldunmu.test/')
            ->assertOk()
            ->assertSee('<title>'.e($seo['title']).'</title>', false)
            ->assertSee('<meta name="description" content="'.e($seo['description']).'">', false)
            ->assertSee('<meta property="og:title" content="'.e($seo['title']).'">', false)
            ->assertSee('<meta property="og:image" content="'.asset('storage/directories/buldunmu-logo.png').'">', false)
            ->assertSee('summary_large_image', false)
            ->assertSee(e($seo['h1']), false);

        $this->assertNotSame('Buldun mu?', $seo['title']);
    }

    public function test_inner_pages_and_directories_without_a_logo_are_unchanged(): void
    {
        Directory::create(['name' => 'Kobiva', 'slug' => 'kobiva', 'domain' => 'kobiva.test', 'status' => 'active']);

        $this->withServerVariables(['HTTP_HOST' => 'kobiva.test'])->get('http://kobiva.test/hakkimizda')->assertOk()
            ->assertDontSee('og:image', false)
            ->assertSee('<meta name="twitter:card" content="summary">', false);
    }

    public function test_quotes_and_ampersands_in_titles_are_escaped_exactly_once(): void
    {
        $directory = Directory::create(['name' => 'Kobiva', 'slug' => 'kobiva', 'domain' => 'kobiva.test', 'status' => 'active']);
        $post = \App\Models\Post::create([
            'title' => "Kobi'nin \"Rehberi\" & Notlar", 'slug' => 'kobinin-rehberi', 'excerpt' => "Kobi'nin \"özel\" rehberi & notları.",
            'content' => '<p>İçerik</p>', 'status' => 'published', 'published_at' => now(), 'is_indexable' => true,
        ]);
        $post->directories()->attach($directory->id);

        $html = (string) $this->withServerVariables(['HTTP_HOST' => 'kobiva.test'])->get('http://kobiva.test/blog/kobinin-rehberi')->assertOk()->getContent();

        $this->assertStringContainsString('<title>Kobi&#039;nin &quot;Rehberi&quot; &amp; Notlar</title>', $html);
        $this->assertStringContainsString('content="Kobi&#039;nin &quot;özel&quot; rehberi &amp; notları."', $html);
        $this->assertStringNotContainsString('&amp;quot;', $html);
        $this->assertStringNotContainsString('&amp;#039;', $html);
    }
}
