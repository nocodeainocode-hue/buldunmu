<?php

namespace Tests\Unit;

use App\Models\Post;
use PHPUnit\Framework\TestCase;

class PostSeoTest extends TestCase
{
    public function test_indexable_post_returns_index_directive(): void
    {
        $post = new Post(['is_indexable' => true]);

        $this->assertStringStartsWith('index,follow', $post->robotsDirective());
    }

    public function test_non_indexable_post_returns_noindex_directive(): void
    {
        $post = new Post(['is_indexable' => false]);

        $this->assertSame('noindex,follow,max-image-preview:large', $post->robotsDirective());
    }
}
