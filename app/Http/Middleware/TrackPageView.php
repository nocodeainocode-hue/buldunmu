<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\PageView;
use App\Support\BotDetector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * Handle an incoming request — log the page view.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET/HEAD requests; skip assets, AJAX, and admin panel
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $response;
        }

        // Skip non-renderable routes
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return $response;
        }

        $path = $request->path();

        // Skip asset paths and admin/internal routes
        if ($this->shouldSkipPath($path)) {
            return $response;
        }

        // Botlar ve hata sayfaları istatistiği şişirmesin
        if (! $response->isSuccessful() || BotDetector::isBot($request->userAgent())) {
            return $response;
        }

        // Resolve directory_id from the app container (set by SetCurrentDirectory middleware)
        $directoryId = app()->bound('currentDirectory') ? app('currentDirectory')->id : null;
        $companyId = null;
        $routeName = $request->route()?->getName();
        $routeSlug = $routeName === 'companies.show' ? $request->route('slug') : null;
        $visit = [
            'path'               => '/' . ltrim($path, '/'),
            'ip_hash'            => hash('sha256', (string) $request->ip()),
            'user_agent_summary' => $this->summarizeUserAgent($request->userAgent()),
            'directory_id'       => $directoryId,
            'created_at'         => now(),
        ];

        // Yanıt ziyaretçiye gönderildikten sonra yazılır: sayfa hızını etkilemez
        app()->terminating(function () use ($visit, $routeSlug): void {
            try {
                if (is_string($routeSlug)) {
                    $visit['company_id'] = Company::withoutGlobalScopes()
                        ->where('slug', $routeSlug)
                        ->when($visit['directory_id'], fn ($q) => $q->where('directory_id', $visit['directory_id']))
                        ->value('id');
                }

                PageView::create($visit);
            } catch (\Throwable) {
                // Silently ignore logging failures — never break the user experience
            }
        });

        return $response;
    }

    private function shouldSkipPath(string $path): bool
    {
        $skipPrefixes = [
            'admin', 'livewire', '_debugbar', 'telescope',
            'horizon', 'api', 'sanctum', 'broadcasting', 'r/', 'ad/',
        ];

        foreach ($skipPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        // Skip common asset extensions
        $skipExtensions = ['css', 'js', 'map', 'png', 'jpg', 'jpeg', 'gif', 'svg',
            'ico', 'woff', 'woff2', 'ttf', 'eot', 'json', 'xml'];

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext && in_array($ext, $skipExtensions, true)) {
            return true;
        }

        return false;
    }

    private function resolveCompanyId(Request $request): ?int
    {
        // Check for company slug in route (company detail page)
        if ($request->route()) {
            // Pattern: /firma/{slug} shows company detail
            $routeName = $request->route()->getName();

            if ($routeName === 'companies.show') {
                $company = $request->route('slug');
                if ($company instanceof \App\Models\Company) {
                    return $company->id;
                }
                // If route model binding didn't resolve, try by slug
                if (is_string($company)) {
                    $found = \App\Models\Company::where('slug', $company)->first();
                    return $found?->id;
                }
            }
        }

        return null;
    }

    private function summarizeUserAgent(?string $ua): ?string
    {
        if (blank($ua)) {
            return null;
        }

        // Extract browser + OS in a compact form (max ~100 chars)
        $browser = 'Other';
        $os = 'Other';

        // OS detection
        if (str_contains($ua, 'Windows NT 10')) {
            $os = 'Windows 10';
        } elseif (str_contains($ua, 'Windows NT')) {
            $os = 'Windows';
        } elseif (str_contains($ua, 'Mac OS X') || str_contains($ua, 'Macintosh')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'Linux') && !str_contains($ua, 'Android')) {
            $os = 'Linux';
        } elseif (str_contains($ua, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $os = 'iOS';
        }

        // Browser detection
        if (str_contains($ua, 'Edg/') || str_contains($ua, 'Edge/')) {
            $browser = 'Edge';
        } elseif (str_contains($ua, 'Chrome/') && !str_contains($ua, 'Edg/')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'Firefox/')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'OPR/') || str_contains($ua, 'Opera/')) {
            $browser = 'Opera';
        }

        return "{$browser} / {$os}";
    }
}
