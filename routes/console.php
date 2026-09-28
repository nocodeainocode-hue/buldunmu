<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check due campaign listings throughout the day; the command enforces the daily limit.
Schedule::command('listings:publish-daily')->everyFiveMinutes()->withoutOverlapping(30);
