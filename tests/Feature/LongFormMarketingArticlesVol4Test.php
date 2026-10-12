<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Database\Seeders\DirectorySeoStoriesSeeder;
use Database\Seeders\LongFormMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesVol2Seeder;
use Database\Seeders\LongFormMarketingArticlesVol3Seeder;
use Database\Seeders\LongFormMarketingArticlesVol4Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LongFormMarketingArticlesVol4Test extends TestCase
{
    use RefreshDatabase;

    /** Rehber önem listesindeki (docs/rehber-stratejisi) ilk 20 rehber, sırayla. */
    private const DOMAINS = [
        'kobiva.com.tr', 'buldunmu.com.tr', 'yerelio.com.tr', 'buldora.com.tr', 'firmondo.com.tr',
        'secmedenonce.com.tr', 'yerelix.com.tr', 'esnafpusulasi.com.tr', 'ustarotasi.com.tr', 'alternatifbul.com.tr',
        'firmafeneri.com.tr', 'komsufirma.com.tr', 'isletmebulutu.com.tr', 'sektorbul.com.tr', 'firmakiyasla.com.tr',
        'ustaharita.com.tr', 'firmaendeksi.com.tr', 'yakindafirma.com.tr', 'yereldurak.com.tr', 'hizmetyakinda.com.tr',
    ];

    public function test_twenty_articles_follow_the_directory_priority_list_and_have_clean_seo_fields(): void
    {
        $articles = LongFormMarketingArticlesVol4Seeder::articles();

        $this->assertSame(self::DOMAINS, array_keys($articles));

        $previous = array_merge(
            array_values(DirectoryArticlesOctober2026Seeder::articles()),
            array_values(DirectoryMarketingArticlesSeeder::articles()),
            array_values(DirectorySeoStoriesSeeder::articles()),
            array_values(LongFormMarketingArticlesSeeder::articles()),
            array_values(LongFormMarketingArticlesVol2Seeder::articles()),
            array_values(LongFormMarketingArticlesVol3Seeder::articles()),
            array_values(require database_path('content/registration_articles.php')),
            array_values(require database_path('content/registration_articles_more.php')),
        );
        foreach (['title', 'slug', 'primary_query'] as $field) {
            $this->assertSame([], array_values(array_intersect(array_column($articles, $field), array_column($previous, $field))), $field);
        }

        foreach ($articles as $domain => $article) {
            // Sayfa başlığı arama sonuçlarında kesilmemeli; açıklama 120-155 karakter olmalı.
            $this->assertLessThanOrEqual(60, mb_strlen($article['title']), $domain.' başlığı çok uzun: '.$article['title']);
            $this->assertGreaterThanOrEqual(30, mb_strlen($article['title']), $domain);
            $this->assertGreaterThanOrEqual(120, mb_strlen($article['excerpt']), $domain.' açıklaması kısa');
            $this->assertLessThanOrEqual(155, mb_strlen($article['excerpt']), $domain.' açıklaması uzun');
            $this->assertNotSame($article['title'], $article['excerpt']);

            $this->assertGreaterThanOrEqual(500, LongFormMarketingArticlesSeeder::bodyWordCount($article['content']), $domain);
            $this->assertGreaterThanOrEqual(5, substr_count($article['content'], '<h2>'), $domain);
            $this->assertStringContainsString('href="/firma-kayit"', $article['content']);
            $this->assertStringContainsString('href="/firmalar"', $article['content']);
            $this->assertStringNotContainsString('<script', $article['content']);
            // Rehberler bağımsız görünür: ağ ya da rehber sayısı geçmez.
            $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|il|site)\b/iu', $article['content']);
        }

        $this->assertCount(20, array_unique(array_column($articles, 'title')));
    }

    public function test_articles_publish_only_on_their_own_directory_and_seeding_is_repeatable(): void
    {
        $articles = LongFormMarketingArticlesVol4Seeder::articles();

        foreach (self::DOMAINS as $domain) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        $original = Post::create(['title' => 'Mevcut yazı', 'slug' => 'mevcut-yazi', 'content' => 'Korunacak', 'status' => 'published', 'published_at' => now()]);

        $this->seed(LongFormMarketingArticlesVol4Seeder::class);
        $this->assertSame(21, Post::count());

        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();

            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);

            app()->instance('currentDirectory', $directory);
            $page = $this->get('/blog/'.$post->slug)->assertOk();
            $page->assertSee('<title>'.e($article['title']).'</title>', false);
            $page->assertSee('<meta name="description" content="'.e($article['excerpt']).'">', false);
        }

        $edited = Post::where('slug', array_values($articles)[0]['slug'])->firstOrFail();
        $edited->update(['title' => 'Editörün düzenlediği başlık']);
        $this->seed(LongFormMarketingArticlesVol4Seeder::class);

        $this->assertSame(21, Post::count());
        $this->assertSame('Editörün düzenlediği başlık', $edited->fresh()->title);
        $this->assertSame('Korunacak', $original->fresh()->content);
    }

    public function test_a_missing_directory_rejects_the_whole_batch(): void
    {
        foreach (array_slice(self::DOMAINS, 0, 19) as $domain) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        try {
            $this->seed(LongFormMarketingArticlesVol4Seeder::class);
            $this->fail('Eksik rehberde yayın reddedilmeliydi.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('hizmetyakinda.com.tr', $exception->getMessage());
        }

        $this->assertSame(0, Post::count());
    }
}
