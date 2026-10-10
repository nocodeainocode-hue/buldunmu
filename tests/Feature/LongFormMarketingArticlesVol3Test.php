<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesVol2Seeder;
use Database\Seeders\LongFormMarketingArticlesVol3Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LongFormMarketingArticlesVol3Test extends TestCase
{
    use RefreshDatabase;

    public function test_new_articles_are_distinct_and_import_once_to_the_requested_directories(): void
    {
        $articles = LongFormMarketingArticlesVol3Seeder::articles();
        $this->assertSame([
            'yerelix.com.tr', 'vipfirma.com.tr', '81ilfirmalar.com.tr', 'kobiva.com.tr', 'kayitlikobi.com.tr',
            'buldunmu.com.tr', 'esnafy.com.tr', 'firmio.com.tr', 'firmayorum.com.tr', 'hizmetto.com.tr',
        ], array_keys($articles));
        $previous = array_merge(
            array_values(DirectoryArticlesOctober2026Seeder::articles()),
            array_values(DirectoryMarketingArticlesSeeder::articles()),
            array_values(LongFormMarketingArticlesSeeder::articles()),
            array_values(LongFormMarketingArticlesVol2Seeder::articles()),
            array_values(require database_path('content/registration_articles.php')),
            array_values(require database_path('content/registration_articles_more.php')),
        );
        foreach (['title', 'slug', 'primary_query'] as $field) {
            $this->assertSame([], array_values(array_intersect(array_column($articles, $field), array_column($previous, $field))), $field);
        }
        foreach ($articles as $domain => $article) {
            $this->assertGreaterThanOrEqual(500, LongFormMarketingArticlesSeeder::bodyWordCount($article['content']));
            $this->assertLessThanOrEqual(160, mb_strlen($article['excerpt']));
            $this->assertStringContainsString('href="/firma-kayit"', $article['content']);
            $this->assertStringNotContainsString('<script', $article['content']);
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }
        $original = Post::create(['title' => 'Mevcut yazı', 'slug' => 'mevcut-yazi', 'content' => 'Korunacak', 'status' => 'published', 'published_at' => now()]);
        $this->seed(LongFormMarketingArticlesVol3Seeder::class);
        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();
            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);
        }
        $this->seed(LongFormMarketingArticlesVol3Seeder::class);
        $this->assertSame(11, Post::count());
        $this->assertSame('Korunacak', $original->fresh()->content);
    }
}
