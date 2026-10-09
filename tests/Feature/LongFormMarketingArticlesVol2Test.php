<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesVol2Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LongFormMarketingArticlesVol2Test extends TestCase
{
    use RefreshDatabase;

    public function test_ten_new_articles_are_unique_long_and_clean(): void
    {
        $articles = LongFormMarketingArticlesVol2Seeder::articles();
        $previous = array_merge(
            array_values(DirectoryArticlesOctober2026Seeder::articles()),
            array_values(DirectoryMarketingArticlesSeeder::articles()),
            array_values(LongFormMarketingArticlesSeeder::articles()),
            array_values(require database_path('content/registration_articles.php')),
            array_values(require database_path('content/registration_articles_more.php')),
        );

        foreach (['title', 'slug', 'primary_query'] as $field) {
            $this->assertSame([], array_values(array_intersect(array_column($articles, $field), array_column($previous, $field))), $field);
        }

        $this->assertCount(10, $articles);

        foreach ($articles as $domain => $article) {
            $this->assertGreaterThanOrEqual(550, LongFormMarketingArticlesSeeder::bodyWordCount($article['content']), $article['slug']);
            $this->assertLessThanOrEqual(160, mb_strlen($article['excerpt']), $article['slug'].' özeti çok uzun');
            $this->assertStringContainsString('href="/firma-kayit"', $article['content']);
            $this->assertStringContainsString('href="/firmalar"', $article['content']);
            $this->assertStringNotContainsString('<script', $article['content']);
            // Rehberler birbirinden bağımsız görünür: ağ ya da rehber sayısı geçmez.
            $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|il)\b/iu', $article['content']);
            $this->assertGreaterThanOrEqual(5, substr_count($article['content'], '<h2>'));
        }
    }

    public function test_articles_publish_only_on_their_directory_and_seeding_is_repeatable(): void
    {
        $articles = LongFormMarketingArticlesVol2Seeder::articles();

        foreach ($articles as $domain => $article) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        $original = Post::create(['title' => 'Mevcut yazı', 'slug' => 'mevcut-yazi', 'content' => 'Korunacak', 'status' => 'published', 'published_at' => now()]);

        $this->seed(LongFormMarketingArticlesVol2Seeder::class);
        $this->assertSame(11, Post::count());

        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();

            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);

            app()->instance('currentDirectory', $directory);
            $this->get('/blog/'.$post->slug)->assertOk()->assertSee($post->title);
        }

        $edited = Post::where('slug', array_values($articles)[0]['slug'])->firstOrFail();
        $edited->update(['title' => 'Editörün düzenlediği başlık']);
        $this->seed(LongFormMarketingArticlesVol2Seeder::class);

        $this->assertSame(11, Post::count());
        $this->assertSame('Editörün düzenlediği başlık', $edited->fresh()->title);
        $this->assertSame('Korunacak', $original->fresh()->content);
    }
}
