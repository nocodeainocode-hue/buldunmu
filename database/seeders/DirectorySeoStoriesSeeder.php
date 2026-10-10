<?php

namespace Database\Seeders;

use RuntimeException;

/** Beş rehbere birer uzun, özgün firma rehberi ve SEO yazısı yükler. */
class DirectorySeoStoriesSeeder extends DirectoryArticlesOctober2026Seeder
{
    public static function articles(): array
    {
        $root = database_path('content/directory_seo_stories');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
        $articles = [];

        foreach ($manifest as $entry) {
            if (! preg_match('/^\d{2}-[a-z0-9-]+\.html$/', $entry['file']) || isset($articles[$entry['domain']])) {
                throw new RuntimeException('Makale dosyası veya rehber eşleştirmesi geçersiz.');
            }
            $content = file_get_contents($root.'/'.$entry['file']);
            if (LongFormMarketingArticlesSeeder::bodyWordCount($content) < 800) {
                throw new RuntimeException("Makale gövdesi 800 kelimeden kısa: {$entry['file']}");
            }
            $articles[$entry['domain']] = [
                'title' => $entry['title'],
                'slug' => $entry['slug'],
                'primary_query' => $entry['primary_query'],
                'excerpt' => $entry['excerpt'],
                'content' => $content,
                'sources' => $entry['sources'],
                'content_type' => 'guide',
                'search_intent' => 'informational',
            ];
        }
        if (count($articles) !== 5
            || count(array_unique(array_column($articles, 'slug'))) !== 5
            || count(array_unique(array_column($articles, 'primary_query'))) !== 5) {
            throw new RuntimeException('Beş farklı rehber, yazı adresi ve ana sorgu bulunmalı.');
        }

        return $articles;
    }
}
