<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Database\Seeders\LongFormMarketingArticlesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class LongFormMarketingArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_ten_long_articles_are_unique_scoped_and_repeatable(): void
    {
        $articles = LongFormMarketingArticlesSeeder::articles();
        $previous = array_merge(
            array_values(DirectoryArticlesOctober2026Seeder::articles()),
            array_values(DirectoryMarketingArticlesSeeder::articles()),
            array_values(require database_path('content/registration_articles.php')),
            array_values(require database_path('content/registration_articles_more.php')),
        );
        foreach (['title', 'slug', 'primary_query'] as $field) {
            $this->assertSame([], array_values(array_intersect(array_column($articles, $field), array_column($previous, $field))));
        }
        foreach ($articles as $domain => $article) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
            $this->assertGreaterThanOrEqual(500, LongFormMarketingArticlesSeeder::bodyWordCount($article['content']));
        }
        $original = Post::create(['title' => 'Mevcut yazı', 'slug' => 'mevcut-yazi', 'content' => 'Korunacak', 'status' => 'published', 'published_at' => now()]);
        $this->seed(LongFormMarketingArticlesSeeder::class);
        $this->assertSame(11, Post::count());
        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();
            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame([$post->id], Post::publishedForDirectory($directory)->pluck('posts.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);
        }
        $edited = Post::where('slug', array_values($articles)[0]['slug'])->firstOrFail();
        $edited->update(['title' => 'Editörün düzenlediği başlık']);
        $this->seed(LongFormMarketingArticlesSeeder::class);
        $this->assertSame(11, Post::count());
        $this->assertSame('Editörün düzenlediği başlık', $edited->fresh()->title);
        $this->assertSame('Korunacak', $original->fresh()->content);
    }

    public function test_missing_directory_rejects_the_whole_batch(): void
    {
        $articles = LongFormMarketingArticlesSeeder::articles();
        array_pop($articles);
        foreach ($articles as $domain => $article) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain]);
        }
        try {
            $this->seed(LongFormMarketingArticlesSeeder::class);
            $this->fail('An incomplete directory set must reject the import.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Rehber bulunamadı', $exception->getMessage());
        }
        $this->assertSame(0, Post::count());
    }
}
