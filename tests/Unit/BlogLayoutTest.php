<?php

namespace Tests\Unit;

use App\Support\BlogLayout;
use PHPUnit\Framework\TestCase;

class BlogLayoutTest extends TestCase
{
    public function test_known_layout_is_preserved(): void
    {
        $this->assertSame('comparison', BlogLayout::normalize('comparison'));
    }

    public function test_unknown_layout_falls_back_to_editorial(): void
    {
        $this->assertSame('editorial', BlogLayout::normalize('unknown'));
        $this->assertSame('editorial', BlogLayout::normalize(null));
    }
}
