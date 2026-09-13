<?php

namespace App\Support;

class PageContent
{
    public static function isMeaningful(mixed $content): bool
    {
        if (! is_string($content)) {
            return false;
        }

        $text = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xc2\xa0", '', $text);

        return trim($text) !== '';
    }
}
