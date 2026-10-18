<?php

namespace Tests\Unit;

use App\Support\SeoSchema;
use PHPUnit\Framework\TestCase;

class SeoSchemaOpeningHoursTest extends TestCase
{
    private function hours(string $text): array
    {
        $method = new \ReflectionMethod(SeoSchema::class, 'openingHours');

        return collect($method->invoke(null, $text))->map(fn ($s) => $s['dayOfWeek'].' '.$s['opens'].'-'.$s['closes'])->all();
    }

    public function test_saturday_is_not_read_as_friday_and_monday_not_as_sunday(): void
    {
        $this->assertSame(['Saturday 10:00-14:00'], $this->hours('Cumartesi 10:00-14:00'));
        $this->assertSame(['Monday 09:00-18:00'], $this->hours('Pazartesi 09:00-18:00'));
        $this->assertSame(['Friday 09:00-17:00'], $this->hours('Cuma 09:00-17:00'));
        $this->assertSame(['Sunday 11:00-15:00'], $this->hours('Pazar 11:00-15:00'));
    }

    public function test_day_ranges_expand_to_every_day(): void
    {
        $this->assertSame([
            'Monday 09:00-18:00', 'Tuesday 09:00-18:00', 'Wednesday 09:00-18:00', 'Thursday 09:00-18:00', 'Friday 09:00-18:00',
            'Saturday 10:00-14:00',
        ], $this->hours("Pazartesi - Cuma 09:00-18:00\nCumartesi 10:00-14:00"));
    }
}
