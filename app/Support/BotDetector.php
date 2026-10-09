<?php

namespace App\Support;

class BotDetector
{
    public static function isBot(?string $userAgent): bool
    {
        if (blank($userAgent)) {
            return true;
        }

        return (bool) preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview|headless|lighthouse|pingdom|uptime|monitor|curl|wget|python|httpclient|axios|go-http|java\/|libwww|scrapy|semrush|ahrefs|mj12/i', $userAgent);
    }
}
