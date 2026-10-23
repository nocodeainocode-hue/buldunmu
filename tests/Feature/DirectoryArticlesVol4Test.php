<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryArticlesVol3Seeder;
use Database\Seeders\DirectoryArticlesVol4Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Database\Seeders\DirectorySeoStoriesSeeder;
use Database\Seeders\LongFormMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesVol2Seeder;
use Database\Seeders\LongFormMarketingArticlesVol3Seeder;
use Database\Seeders\LongFormMarketingArticlesVol4Seeder;
use Database\Seeders\LongFormMarketingArticlesVol5Seeder;
use Database\Seeders\LongFormMarketingArticlesVol7Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryArticlesVol4Test extends TestCase
{
    use RefreshDatabase;

    public function test_eighty_three_articles_have_clean_unique_seo_fields(): void
    {
        $articles = DirectoryArticlesVol4Seeder::articles();
        $this->assertCount(83, $articles);

        $previous = array_merge(
            array_values(DirectoryArticlesOctober2026Seeder::articles()),
            array_values(DirectoryMarketingArticlesSeeder::articles()),
            array_values(DirectorySeoStoriesSeeder::articles()),
            array_values(LongFormMarketingArticlesSeeder::articles()),
            array_values(LongFormMarketingArticlesVol2Seeder::articles()),
            array_values(LongFormMarketingArticlesVol3Seeder::articles()),
            array_values(LongFormMarketingArticlesVol4Seeder::articles()),
            array_values(LongFormMarketingArticlesVol5Seeder::articles()),
            array_values(LongFormMarketingArticlesVol7Seeder::articles()),
            array_values(DirectoryArticlesVol3Seeder::articles()),
        );
        foreach (['title', 'slug', 'primary_query'] as $field) {
            $this->assertSame([], array_values(array_intersect(array_column($articles, $field), array_column($previous, $field))), $field);
        }

        foreach ($articles as $domain => $article) {
            $this->assertLessThanOrEqual(70, mb_strlen($article['title']), $domain.' başlığı çok uzun: '.$article['title']);
            $this->assertGreaterThanOrEqual(30, mb_strlen($article['title']), $domain);
            $this->assertGreaterThanOrEqual(120, mb_strlen($article['excerpt']), $domain.' açıklaması kısa');
            $this->assertLessThanOrEqual(160, mb_strlen($article['excerpt']), $domain.' açıklaması uzun');
            $this->assertNotSame($article['title'], $article['excerpt']);
            $this->assertMatchesRegularExpression('/^[a-z0-9]+(-[a-z0-9]+)*$/', $article['slug']);

            $this->assertGreaterThanOrEqual(500, LongFormMarketingArticlesSeeder::bodyWordCount($article['content']), $domain);
            $this->assertGreaterThanOrEqual(4, substr_count($article['content'], '<h2>'), $domain);
            $this->assertStringContainsString('href="/firma-kayit"', $article['content']);
            $this->assertStringContainsString('href="/firmalar"', $article['content']);
            $this->assertStringNotContainsString('<script', $article['content']);
            $this->assertStringNotContainsString('<h1', $article['content']);
            // Rehberler bağımsız görünür: ağ ya da rehber sayısı geçmez.
            $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|il|site)\b/iu', $article['content']);
            $this->assertDoesNotMatchRegularExpression('/diğer rehber|rehber ağı/iu', $article['content']);
        }

        $this->assertCount(83, array_unique(array_column($articles, 'title')));
    }

    public function test_articles_publish_only_on_their_own_directory_and_seeding_is_repeatable(): void
    {
        $articles = DirectoryArticlesVol4Seeder::articles();

        foreach (array_keys($articles) as $domain) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        $this->seed(DirectoryArticlesVol4Seeder::class);
        $this->assertSame(83, Post::count());

        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();

            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);
        }

        $this->seed(DirectoryArticlesVol4Seeder::class);
        $this->assertSame(83, Post::count());
    }
}
