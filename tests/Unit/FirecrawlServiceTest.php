<?php

namespace Tests\Unit;

use App\Services\FirecrawlService;
use Tests\TestCase;

class FirecrawlServiceTest extends TestCase
{
    public function test_service_is_instantiable(): void
    {
        $service = app(FirecrawlService::class);
        $this->assertInstanceOf(FirecrawlService::class, $service);
    }

    public function test_api_key_loaded_from_config(): void
    {
        config(['services.firecrawl.key' => 'fc-test-123']);
        $service = app(FirecrawlService::class);

        $ref = new \ReflectionClass($service);
        $prop = $ref->getProperty('apiKey');
        $prop->setAccessible(true);

        $this->assertEquals('fc-test-123', $prop->getValue($service));
    }

    public function test_base_url_is_correct(): void
    {
        $service = app(FirecrawlService::class);
        $ref = new \ReflectionClass($service);
        $prop = $ref->getProperty('baseUrl');
        $prop->setAccessible(true);

        $this->assertEquals('https://api.firecrawl.dev/v2/', $prop->getValue($service));
    }

    public function test_error_sanitizer_redacts_auth_header(): void
    {
        $service = app(FirecrawlService::class);
        $ref = new \ReflectionClass($service);
        $method = $ref->getMethod('sanitizeError');
        $method->setAccessible(true);

        // Simulate an error message containing auth header
        $exception = new \GuzzleHttp\Exception\RequestException(
            'Error: POST /search failed. Authorization header present. 401 Unauthorized.',
            new \GuzzleHttp\Psr7\Request('POST', '/search')
        );

        $result = $method->invoke($service, $exception);

        // Result should still contain the error description
        $this->assertStringContainsString('Error:', $result);
        $this->assertStringContainsString('401', $result);
    }
}
