<?php

namespace Database\Seeders;

use RuntimeException;

/** Twenty original guides about local SEO and business directories. */
class LocalSeoDirectoryArticlesSeeder extends LongFormMarketingArticlesSeeder
{
    protected const CONTENT_DIR = 'content/deep_local_seo_articles';
    protected const EXPECTED_COUNT = 20;

    public static function articles(): array
    {
        $root = database_path(static::CONTENT_DIR);
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
        $articles = parent::articles();

        foreach ($manifest as $entry) {
            $domain = $entry['domain'];
            $article = $articles[$domain] ?? null;

            if (! $article) {
                throw new RuntimeException("Makale rehber eşlemesi bulunamadı: {$domain}");
            }

            $wordCount = self::bodyWordCount($article['content']);
            if ($wordCount < 1000 || $wordCount > 1500) {
                throw new RuntimeException("Makale gövdesi 1.000–1.500 kelime aralığında değil ({$wordCount}): {$entry['file']}");
            }

            $articles[$domain]['faq_items'] = $entry['faq_items'];
        }

        return $articles;
    }
}
