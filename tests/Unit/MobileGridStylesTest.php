<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tek sütuna düşen ızgaralar "1fr" yerine "minmax(0,1fr)" kullanmalı: aksi halde sütun, içindeki
 * uzun (nowrap) bir başlık yüzünden telefonda ekrandan geniş olur ve sayfa yana taşar.
 */
class MobileGridStylesTest extends TestCase
{
    public function test_single_column_grids_in_theme_styles_cannot_grow_past_the_viewport(): void
    {
        $files = glob(dirname(__DIR__, 2).'/resources/views/partials/*/styles.blade.php');

        $this->assertNotEmpty($files);

        foreach ($files as $file) {
            $this->assertDoesNotMatchRegularExpression(
                '/grid-template-columns:\s*1fr\s*[;}]/',
                file_get_contents($file),
                basename(dirname($file)).' teması tek sütunda 1fr kullanıyor; minmax(0,1fr) kullanın.'
            );
        }
    }
}
