<?php

namespace Tests\Feature;

use App\Http\Middleware\CacheHeaders;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CacheHeadersTest extends TestCase
{
    public function test_admin_and_livewire_responses_are_never_publicly_cached(): void
    {
        $middleware = new CacheHeaders;

        foreach (['/admin/analytics-dashboard', '/livewire-f7a7010e/update'] as $path) {
            $request = Request::create($path, str_contains($path, 'livewire') ? 'POST' : 'GET');
            $response = $middleware->handle($request, fn () => new Response('ok'));

            $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
        }
    }

    public function test_frontend_get_responses_keep_the_public_cache_policy(): void
    {
        $request = Request::create('/firmalar', 'GET');
        $response = (new CacheHeaders)->handle($request, fn () => new Response('ok'));

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertSame('3600', $response->headers->getCacheControlDirective('max-age'));
    }
}
