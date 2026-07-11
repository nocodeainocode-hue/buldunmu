<?php

namespace Tests\Unit;

use App\Services\CompanySlugService;
use PHPUnit\Framework\TestCase;

class CompanySlugServiceTest extends TestCase
{
    public function test_it_renders_supported_placeholders_as_a_clean_slug(): void
    {
        $slug = (new CompanySlugService())->render('{city}-{category}-{name}', [
            'name' => 'Eyüp Market',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'category' => 'Süper Market',
        ]);

        $this->assertSame('istanbul-super-market-eyup-market', $slug);
    }

    public function test_pattern_distribution_repeats_after_ten_directories(): void
    {
        $this->assertCount(10, CompanySlugService::selectOptions());
        $this->assertSame(CompanySlugService::patternForPosition(0), CompanySlugService::patternForPosition(10));
        $this->assertSame('{name}', CompanySlugService::patternForPosition(0));
        $this->assertSame('{name}-{city}', CompanySlugService::patternForPosition(1));
    }
}
