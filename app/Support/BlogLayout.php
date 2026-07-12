<?php

namespace App\Support;

class BlogLayout
{
    public const OPTIONS = [
        'editorial' => 'Editoryal Dergi',
        'local' => 'Yerel Gazete',
        'comparison' => 'Karşılaştırma Masası',
        'alternatives' => 'Alternatif Kataloğu',
        'answers' => 'Uzman Cevapları',
    ];

    public static function normalize(?string $layout): string
    {
        return array_key_exists((string) $layout, self::OPTIONS) ? $layout : 'editorial';
    }
}
