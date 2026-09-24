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

    public function test_dynamic_frontend_pages_are_not_cached_after_content_changes(): void
    {
        $request = Request::create('/firmalar', 'GET');
        $response = (new CacheHeaders)->handle($request, fn () => new Response('ok'));

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_static_asset_responses_can_still_be_cached(): void
    {
        $request = Request::create('/assets/app.css', 'GET');
        $response = (new CacheHeaders)->handle($request, fn () => new Response('body{}'));

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertSame('31536000', $response->headers->getCacheControlDirective('max-age'));
    }
}
