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

    public function test_latest_history_entry_matches_the_current_version(): void
    {
        $latest = ApplicationVersion::history()[0] ?? null;

        $this->assertNotNull($latest);
        $this->assertSame(config('version.number'), $latest['number']);
        $this->assertSame(config('version.name'), $latest['name']);
        $this->assertSame(config('version.released_at'), $latest['released_at']);
        $this->assertNotEmpty($latest['changes']);
    }
}
