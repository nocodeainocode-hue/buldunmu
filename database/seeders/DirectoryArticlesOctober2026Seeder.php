<?php

namespace Database\Seeders;

use App\Models\Directory;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class DirectoryArticlesOctober2026Seeder extends Seeder
{
    protected const CONTENT_FILE = 'content/directory_articles_2026_10.json';

    public static function articles(): array
    {
        $rows = json_decode(file_get_contents(database_path(static::CONTENT_FILE)), true, 512, JSON_THROW_ON_ERROR);
        $articles = [];

        foreach ($rows as $row) {
            if (count($row) !== 11) {
                throw new RuntimeException('Yazı dosyasındaki alan sayısı geçersiz.');
            }

            [$domain, $title, $query, $type, $intent, $intro, $headingOne, $paragraphOne, $headingTwo, $paragraphTwo, $invitation] = $row;

            if (isset($articles[$domain])) {
                throw new RuntimeException("Tekrarlanan rehber: {$domain}");
            }

            $articles[$domain] = [
                'title' => $title,
                'slug' => Str::slug($title),
                'content_type' => $type,
                'primary_query' => $query,
                'search_intent' => $intent,
                'excerpt' => $intro,
                'content' => '<p>'.e($intro).'</p><h2>'.e($headingOne).'</h2><p>'.e($paragraphOne)
                    .'</p><h2>'.e($headingTwo).'</h2><p>'.e($paragraphTwo).'</p><p>'.e($invitation)
                    .' <a href="/firmalar">Firma rehberini inceleyin</a> veya <a href="/firma-kayit">işletmenizin firma kaydını oluşturun</a>.</p>',
            ];
        }

        if (count($articles) !== 83
            || count(array_unique(array_column($articles, 'slug'))) !== 83
            || count(array_unique(array_column($articles, 'primary_query'))) !== 83) {
            throw new RuntimeException('83 farklı rehber, yazı adresi ve ana sorgu bulunmalı.');
        }

        return $articles;
    }

    public function run(): void
    {
        $articles = static::articles();
        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($articles, &$created, &$skipped): void {
            $directories = Directory::whereIn('domain', array_keys($articles))->get()->keyBy('domain');

            // Validate the entire batch before writing anything. No partial publication.
            foreach ($articles as $domain => $article) {
                $directory = $directories->get($domain);

                if (! $directory) {
                    throw new RuntimeException("Rehber bulunamadı: {$domain}");
                }

                $existing = Post::where('slug', $article['slug'])->first();

                if ($existing && $existing->directories()->pluck('directories.id')->all() !== [$directory->id]) {
                    throw new RuntimeException("Yazı adresi başka bir rehberde kullanılıyor: {$article['slug']}");
                }

                if (Post::where('primary_query', $article['primary_query'])
                    ->when($existing, fn ($query) => $query->whereKeyNot($existing->id))->exists()) {
                    throw new RuntimeException("Ana sorgu başka bir yazıda kullanılıyor: {$article['primary_query']}");
                }
            }

            foreach ($articles as $domain => $article) {
                // Preserve published articles and subsequent editor changes on repeated imports.
                if (Post::where('slug', $article['slug'])->exists()) {
                    $skipped++;

                    continue;
                }

                $post = Post::create([
                    ...$article,
                    'status' => 'published',
                    'published_at' => now(),
                    'is_indexable' => true,
                ]);
                $post->directories()->attach($directories[$domain]->id);
                $created++;
            }
        });

        $this->command?->info(class_basename(static::class).": {$created} eklendi, {$skipped} mevcut yazı korundu; toplam 83 rehber.");
    }
}
