<?php

namespace Database\Seeders;

use RuntimeException;

/** İstanbul, Ankara, İzmir ve Trakya için dört özgün uzun makale. */
class LongFormRegionalArticlesSeeder extends LongFormMarketingArticlesSeeder
{
    protected const CONTENT_DIR = 'content/deep_marketing_articles_6';
    protected const EXPECTED_COUNT = 4;

    public static function articles(): array
    {
        $articles = parent::articles();

        foreach ($articles as $domain => $article) {
            $wordCount = self::bodyWordCount($article['content']);

            if ($wordCount < 700 || $wordCount > 1500) {
                throw new RuntimeException("Makale 700–1.500 kelime aralığında değil ({$wordCount}): {$domain}");
            }
        }

        return $articles;
    }
}
