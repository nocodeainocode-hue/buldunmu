<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Apply cache headers for static assets and dynamic pages.
     *
     * - Static assets (css, js, woff2, jpg, png, svg, ico): 1 year immutable
     * - Dynamic pages: never reuse a stale response after content changes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('admin*') || $request->is('livewire*') || ! $request->isMethodCacheable()) {
            $response->headers->set('Cache-Control', 'no-store, private');

            return $response;
        }

        if (! $response->isSuccessful() || $response->headers->has('Set-Cookie')) {
            $response->headers->set('Cache-Control', 'no-store, private');

            return $response;
        }

        $ext = pathinfo($request->path(), PATHINFO_EXTENSION);

        if (in_array($ext, ['css', 'js', 'woff2', 'jpg', 'png', 'svg', 'ico'])) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } else {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }
}
