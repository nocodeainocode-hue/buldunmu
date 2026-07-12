<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Directory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->directory = Directory::create([
            'name' => 'Test Rehberi',
            'slug' => 'test-rehberi',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
    }

    public function test_published_posts_appear_in_blog_listing(): void
    {
        $post = Post::create([
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post',
            'content' => 'Test content',
            'status' => 'published',
            'published_at' => now(),
            'directory_id' => $this->directory->id,
        ]);
        $post->directories()->attach($this->directory->id);

        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Test Blog Post');
    }

    public function test_draft_posts_do_not_appear(): void
    {
        $post = Post::create([
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'content' => 'Draft content',
            'status' => 'draft',
            'published_at' => now(),
            'directory_id' => $this->directory->id,
        ]);
        $post->directories()->attach($this->directory->id);

        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertDontSee('Draft Post');
    }

    public function test_sitemap_includes_published_posts(): void
    {
        $post = Post::create([
            'title' => 'Sitemap Post',
            'slug' => 'sitemap-post',
            'content' => 'Content',
            'status' => 'published',
            'published_at' => now(),
            'directory_id' => $this->directory->id,
        ]);
        $post->directories()->attach($this->directory->id);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertSee('sitemap-post');
    }

    public function test_robots_txt_returns_sitemap_url(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertSee('Sitemap:');
        $response->assertSee('sitemap.xml');
        $response->assertSee('Disallow: /admin/');
    }

    public function test_noindex_post_is_excluded_from_sitemap(): void
    {
        $post = Post::create([
            'title' => 'Dahili Rehber',
            'slug' => 'dahili-rehber',
            'content' => 'İçerik',
            'status' => 'published',
            'published_at' => now(),
            'is_indexable' => false,
            'directory_id' => $this->directory->id,
        ]);
        $post->directories()->attach($this->directory->id);

        $this->get('/sitemap.xml')->assertDontSee('dahili-rehber');
    }

    public function test_directory_blog_layout_changes_listing_structure(): void
    {
        $this->directory->update(['blog_layout' => 'comparison']);
        app()->instance('currentDirectory', $this->directory->fresh());

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Karar Masası');
    }
}
