<?php

namespace Database\Seeders;

use RuntimeException;

class LongFormMarketingArticlesSeeder extends DirectoryArticlesOctober2026Seeder
{
    public static function bodyWordCount(string $content): int
    {
        preg_match_all('/<p>(.*?)<\/p>/su', $content, $paragraphs);
        // Headings and the final registration invitation do not satisfy the minimum.
        $body = implode(' ', array_slice($paragraphs[1], 0, -1));
        $text = trim(html_entity_decode(strip_tags($body), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return $text === '' ? 0 : count(preg_split('/\s+/u', $text));
    }

    public static function articles(): array
    {
        $root = database_path('content/deep_marketing_articles');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
        $articles = [];

        foreach ($manifest as $entry) {
            if (! preg_match('/^\d{2}-[a-z0-9-]+\.html$/', $entry['file'])) {
                throw new RuntimeException('Makale dosya adı geçersiz.');
            }
            if (isset($articles[$entry['domain']])) {
                throw new RuntimeException('Bir rehbere yalnızca bir uzun makale eklenebilir.');
            }
            $content = file_get_contents($root.'/'.$entry['file']);
            if (self::bodyWordCount($content) < 500) {
                throw new RuntimeException("Makale gövdesi 500 kelimeden kısa: {$entry['file']}");
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
        if (count($articles) !== 10
            || count(array_unique(array_column($articles, 'slug'))) !== 10
            || count(array_unique(array_column($articles, 'primary_query'))) !== 10) {
            throw new RuntimeException('10 ayrı rehber, makale adresi ve sorgu bulunmalı.');
        }

        return $articles;
    }
}
