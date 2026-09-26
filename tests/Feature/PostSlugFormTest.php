<?php

namespace Tests\Feature;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostSlugFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_turkish_title_generates_slug_and_keeps_updating_it(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(CreatePost::class)
            ->set('data.title', 'Çığ Şube İçi Özel Güçlü İşletme')
            ->assertSet('data.slug', 'cig-sube-ici-ozel-guclu-isletme')
            ->set('data.title', 'İstanbul Üsküdar Şirketleri')
            ->assertSet('data.slug', 'istanbul-uskudar-sirketleri');
    }

    public function test_manual_slug_is_not_overwritten_by_later_title_changes(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(CreatePost::class)
            ->set('data.title', 'İlk Başlık')
            ->set('data.slug', 'ozel-yazi-adresi')
            ->set('data.title', 'Yeni Başlık')
            ->assertSet('data.slug', 'ozel-yazi-adresi');
    }

    public function test_editing_a_published_title_keeps_its_existing_url(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $post = Post::create([
            'title' => 'Eski Başlık',
            'slug' => 'kalici-yazi-adresi',
            'content' => '<p>İçerik</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
            ->set('data.title', 'Yeni Başlık')
            ->assertSet('data.slug', 'kalici-yazi-adresi');
    }
}
