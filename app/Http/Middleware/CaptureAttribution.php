<?php

namespace App\Http\Middleware;

use App\Services\Attribution;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CaptureAttribution
{
    public function __construct(private Attribution $attribution) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->ajax() && ! $request->is('admin*', 'livewire*', 'panel*', 'ad/*', 'r/*')) {
            $fresh = $this->attribution->fromRequest($request);
            $hasCookie = $request->cookie(Attribution::COOKIE) !== null;

            if ($fresh !== null) {
                // Yeni reklam tıklaması: ilk temasın yerine geçer.
                Cookie::queue(Attribution::COOKIE, json_encode($fresh), Attribution::MINUTES);
            } elseif (! $hasCookie && ($referrer = $this->attribution->externalReferrer($request))) {
                // Reklamsız ilk ziyaret: yalnızca nereden geldiğini not al.
                Cookie::queue(Attribution::COOKIE, json_encode(['referrer_host' => $referrer]), Attribution::MINUTES);
            }
        }

        return $next($request);
    }
}
