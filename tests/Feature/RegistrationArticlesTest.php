<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\Post;
use Database\Seeders\RegistrationArticlesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_article_is_published_only_on_its_directory_and_seeding_is_repeatable(): void
    {
        $articles = array_merge(
            require database_path('content/registration_articles.php'),
            require database_path('content/registration_articles_more.php'),
        );

        $this->assertCount(20, $articles);
        $this->assertCount(20, array_unique(array_column($articles, 'slug')));
        $this->assertCount(20, array_unique(array_column($articles, 'primary_query')));

        foreach ($articles as $domain => $article) {
            Directory::create([
                'name' => $domain,
                'slug' => str_replace('.', '-', $domain),
                'domain' => $domain,
                'status' => 'active',
            ]);
        }

        $this->seed(RegistrationArticlesSeeder::class);
        $this->assertSame(20, Post::count());

        foreach ($articles as $domain => $article) {
            $directory = Directory::where('domain', $domain)->firstOrFail();
            $post = Post::where('slug', $article['slug'])->firstOrFail();

            $this->assertSame('published', $post->status);
            $this->assertNull($post->directory_id);
            $this->assertTrue($post->is_indexable);
            $this->assertNotNull($post->published_at);
            $this->assertSame([$directory->id], $post->directories()->pluck('directories.id')->all());
            $this->assertStringContainsString('href="/firma-kayit"', $post->content);
            $this->assertSame([$post->id], Post::publishedForDirectory($directory)->pluck('posts.id')->all());

            app()->instance('currentDirectory', $directory);
            $this->get('/blog/'.$post->slug)
                ->assertOk()
                ->assertSee($post->title)
                ->assertSee('href="/firma-kayit"', false);
        }

        $editedPost = Post::firstOrFail();
        $editedPost->update(['title' => 'Editörün güncellediği başlık']);
        $this->seed(RegistrationArticlesSeeder::class);

        $this->assertSame(20, Post::count());
        $this->assertSame('Editörün güncellediği başlık', $editedPost->fresh()->title);
    }
}
