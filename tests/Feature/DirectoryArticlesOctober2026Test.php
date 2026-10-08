<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\DirectoryArticlesOctober2026Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryArticlesOctober2026Test extends TestCase
{
    use RefreshDatabase;

    public function test_batch_is_published_to_exactly_one_directory_each_and_can_be_repeated(): void
    {
        $articles = DirectoryArticlesOctober2026Seeder::articles();
        $this->assertCount(83, $articles);

        foreach ($articles as $domain => $article) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        // The first article was already published manually before switching to bulk import.
        $domain = array_key_first($articles);
        $first = Post::create([...$articles[$domain], 'status' => 'published', 'published_at' => now(), 'is_indexable' => true]);
        $first->directories()->attach(Directory::where('domain', $domain)->firstOrFail()->id);
        $first->update(['content' => '<p>Editörün korunan metni</p>']);

        $this->seed(DirectoryArticlesOctober2026Seeder::class);
        $this->assertSame(83, Post::count());

        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();
            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertSame([$post->id], Post::publishedForDirectory($directory)->pluck('posts.id')->all());
            $this->assertTrue($post->is_indexable);
        }

        $this->seed(DirectoryArticlesOctober2026Seeder::class);
        $this->assertSame(83, Post::count());
        $this->assertSame('<p>Editörün korunan metni</p>', $first->fresh()->content);
    }

    public function test_missing_directory_prevents_partial_publication(): void
    {
        $domains = array_keys(DirectoryArticlesOctober2026Seeder::articles());
        array_pop($domains);

        foreach ($domains as $domain) {
            Directory::create(['name' => $domain, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active']);
        }

        try {
            $this->seed(DirectoryArticlesOctober2026Seeder::class);
            $this->fail('Missing directory must reject the batch.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('Rehber bulunamadı', $exception->getMessage());
        }

        $this->assertSame(0, Post::count());
    }
}
