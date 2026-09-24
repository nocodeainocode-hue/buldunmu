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
        app()->instance('currentDirectory', $this->directory);
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

    public function test_unpublished_post_disappears_from_home_and_blog_immediately(): void
    {
        $post = Post::create([
            'title' => 'Geçici Deneme Yazısı',
            'slug' => 'gecici-deneme-yazisi',
            'content' => 'Deneme içeriği',
            'status' => 'published',
            'published_at' => now(),
        ]);
        $post->directories()->attach($this->directory->id);

        $this->get('/')->assertOk()->assertSee('Geçici Deneme Yazısı');
        $this->get('/blog')->assertOk()->assertSee('Geçici Deneme Yazısı');

        $post->update(['status' => 'draft']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Geçici Deneme Yazısı')
            ->assertHeader('Cache-Control', 'no-store, private');
        $this->get('/blog')
            ->assertOk()
            ->assertDontSee('Geçici Deneme Yazısı')
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_general_posts_appear_when_directory_has_no_published_posts(): void
    {
        Post::create([
            'title' => 'Genel Blog Yazisi',
            'slug' => 'genel-blog-yazisi',
            'content' => 'Genel content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Genel Blog Yazisi');
    }

    public function test_directory_posts_replace_general_posts(): void
    {
        Post::create([
            'title' => 'Genel Blog Yazisi',
            'slug' => 'genel-blog-yazisi',
            'content' => 'Genel content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $directoryPost = Post::create([
            'title' => 'Rehbere Ozel Yazi',
            'slug' => 'rehbere-ozel-yazi',
            'content' => 'Ozel content',
            'status' => 'published',
            'published_at' => now(),
            'directory_id' => $this->directory->id,
        ]);
        $directoryPost->directories()->attach($this->directory);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Rehbere Ozel Yazi')
            ->assertDontSee('Genel Blog Yazisi');

        $this->get('/blog/genel-blog-yazisi')->assertNotFound();
    }

    public function test_draft_directory_post_does_not_hide_general_posts(): void
    {
        Post::create([
            'title' => 'Genel Blog Yazisi',
            'slug' => 'genel-blog-yazisi',
            'content' => 'Genel content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $draft = Post::create([
            'title' => 'Taslak Ozel Yazi',
            'slug' => 'taslak-ozel-yazi',
            'content' => 'Taslak content',
            'status' => 'draft',
            'published_at' => now(),
            'directory_id' => $this->directory->id,
        ]);
        $draft->directories()->attach($this->directory);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Genel Blog Yazisi')
            ->assertDontSee('Taslak Ozel Yazi');
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

    public function test_published_rich_text_keeps_editor_structure_and_uses_public_article_styles(): void
    {
        $post = Post::create([
            'title' => 'Biçimli Yazı',
            'slug' => 'bicimli-yazi',
            'content' => '<h2>Önemli başlık</h2><p><strong>Kalın metin</strong></p><ul><li>Birinci madde</li></ul><blockquote><p>Alıntı</p></blockquote>',
            'status' => 'published',
            'published_at' => now(),
        ]);
        $post->directories()->attach($this->directory);

        $this->get('/blog/bicimli-yazi')
            ->assertOk()
            ->assertSee('class="blog-prose mt-9"', false)
            ->assertSee('<h2>Önemli başlık</h2>', false)
            ->assertSee('<ul><li>Birinci madde</li></ul>', false)
            ->assertSee('<blockquote><p>Alıntı</p></blockquote>', false);
    }
}
