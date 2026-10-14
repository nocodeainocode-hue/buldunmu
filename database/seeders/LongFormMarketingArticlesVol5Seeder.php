<?php

namespace Database\Seeders;

use RuntimeException;

/** Seçilen 20 firma rehberi için 700–1.500 kelimelik işletme odaklı makaleler. */
class LongFormMarketingArticlesVol5Seeder extends LongFormMarketingArticlesSeeder
{
    protected const CONTENT_DIR = 'content/deep_marketing_articles_5';
    protected const EXPECTED_COUNT = 20;

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
