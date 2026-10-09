<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Database\Seeders\DirectoryMarketingArticlesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryMarketingArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_batch_preserves_old_articles_and_publishes_exactly_one_per_directory(): void
    {
        $new = DirectoryMarketingArticlesSeeder::articles();
        $old = DirectoryArticlesOctober2026Seeder::articles();
        $older = array_merge(
            require database_path('content/registration_articles.php'),
            require database_path('content/registration_articles_more.php'),
        );
        $this->assertCount(83, $new);
        foreach (['slug', 'primary_query', 'title'] as $field) {
            $this->assertCount(83, array_unique(array_column($new, $field)));
            $this->assertSame([], array_values(array_intersect(array_column($new, $field), array_column($old, $field))));
            $this->assertSame([], array_values(array_intersect(array_column($new, $field), array_column($older, $field))));
        }
        $this->assertSame(array_keys($old), array_keys($new));
        foreach ($new as $domain => $article) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
            $this->assertStringContainsString('href="/firma-kayit"', $article['content']);
            $this->assertGreaterThanOrEqual(95, count(preg_split('/\s+/u', trim(strip_tags(str_replace('</', ' </', $article['content']))))));
        }
        $this->seed(DirectoryArticlesOctober2026Seeder::class);
        $original = Post::orderBy('id')->get()->toArray();
        $this->seed(DirectoryMarketingArticlesSeeder::class);
        $this->assertSame(166, Post::count());
        $this->assertSame($original, Post::orderBy('id')->limit(83)->get()->toArray());
        foreach ($new as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();
            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame('published', $post->status);
            $this->assertTrue($post->is_indexable);
            $this->assertSame(2, Post::publishedForDirectory($directory)->count());
        }
        $this->seed(DirectoryMarketingArticlesSeeder::class);
        $this->assertSame(166, Post::count());
    }
}
