<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Apply cache headers for static assets and HTML pages.
     *
     * - Static assets (css, js, woff2, jpg, png, svg, ico): 1 year immutable
     * - Frontend HTML pages (non-admin): 1 hour
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $ext = pathinfo($request->path(), PATHINFO_EXTENSION);

        if (in_array($ext, ['css', 'js', 'woff2', 'jpg', 'png', 'svg', 'ico'])) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } elseif (!$request->is('admin*')) {
            $response->headers->set('Cache-Control', 'public, max-age=3600');
        }

        return $response;
    }
}
