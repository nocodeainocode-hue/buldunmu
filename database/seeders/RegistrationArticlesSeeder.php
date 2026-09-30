<?php

namespace Database\Seeders;

use App\Models\Directory;
use App\Models\Post;
use Illuminate\Database\Seeder;
use RuntimeException;

class RegistrationArticlesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('content/registration_articles.php') as $domain => $article) {
            $directory = Directory::where('domain', $domain)->first();

            if (! $directory) {
                throw new RuntimeException("Yazı yayımlanamadı: {$domain} rehberi bulunamadı.");
            }

            $slug = $article['slug'];
            $post = Post::where('slug', $slug)->first();

            if ($post && ! $post->directories()->whereKey($directory->id)->exists()) {
                throw new RuntimeException("Yazı yayımlanamadı: {$slug} adresi başka bir rehberde kullanılıyor.");
            }

            if (! $post) {
                $post = Post::create([
                    ...$article,
                    'status' => 'published',
                    'published_at' => now(),
                    'is_indexable' => true,
                ]);
                $post->directories()->attach($directory->id);
            }
        }
    }
}
