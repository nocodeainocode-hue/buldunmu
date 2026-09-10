<?php

namespace Tests\Unit;

use App\Support\ApplicationVersion;
use Tests\TestCase;

class ApplicationVersionTest extends TestCase
{
    public function test_version_label_contains_version_and_short_commit(): void
    {
        config()->set('version.number', '1.2.3');
        config()->set('version.commit', 'abcdef1234567890abcdef1234567890abcdef12');

        $this->assertSame('v1.2.3 · abcdef1', ApplicationVersion::label());
    }
}
