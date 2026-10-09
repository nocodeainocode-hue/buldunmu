<?php

namespace Database\Seeders;

class DirectoryMarketingArticlesSeeder extends DirectoryArticlesOctober2026Seeder
{
    protected const CONTENT_FILE = 'content/directory_marketing_articles_2026_10.json';

    public static function articles(): array
    {
        $articles = parent::articles();
        // Primary sources for specific Google product/policy claims. Other articles
        // are original, practical planning advice rather than ranking promises.
        $references = [
            'cityesnaf.com.tr' => 'https://support.google.com/business/answer/7091?hl=tr',
            'kobiharita.com.tr' => 'https://support.google.com/business/answer/7091?hl=tr',
            'vipfirma.com.tr' => 'https://support.google.com/business/answer/7091?hl=tr',
            'esnafpuani.com.tr' => 'https://support.google.com/business/answer/3474122?hl=tr',
            'kobiva.com.tr' => 'https://developers.google.com/search/docs/essentials/spam-policies',
            'localbul.com.tr' => 'https://developers.google.com/search/docs/essentials/spam-policies',
            'kobimercek.com.tr' => 'https://developers.google.com/search/docs/fundamentals/seo-starter-guide',
            'yerelrehber360.com.tr' => 'https://developers.google.com/search/docs/monitor-debug/search-console-start',
        ];

        foreach ($references as $domain => $url) {
            $articles[$domain]['sources'] = [$url];
        }

        return $articles;
    }
}
