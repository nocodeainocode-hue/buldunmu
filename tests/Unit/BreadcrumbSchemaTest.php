<?php

namespace Tests\Unit;

use App\Support\SeoSchema;
use App\Models\Directory;
use Tests\TestCase;

class BreadcrumbSchemaTest extends TestCase
{
    public function test_breadcrumb_generates_valid_schema(): void
    {
        $breadcrumb = SeoSchema::breadcrumb([
            ['name' => 'Ana Sayfa', 'url' => 'https://test.local'],
            ['name' => 'Kategori', 'url' => 'https://test.local/kategori/test'],
        ], 'https://test.local/kategori/test');

        $this->assertEquals('BreadcrumbList', $breadcrumb['@type']);
        $this->assertCount(2, $breadcrumb['itemListElement']);
        $this->assertEquals('Ana Sayfa', $breadcrumb['itemListElement'][0]['name']);
    }

    public function test_breadcrumb_filters_null_items(): void
    {
        $breadcrumb = SeoSchema::breadcrumb([
            ['name' => 'Ana Sayfa', 'url' => 'https://test.local'],
            null,
            ['name' => 'Son', 'url' => 'https://test.local/son'],
        ], 'https://test.local/son');

        $this->assertCount(2, $breadcrumb['itemListElement']);
    }

    public function test_faq_schema(): void
    {
        $faq = SeoSchema::faqPage('https://test.local/faq', [
            ['question' => 'Nasıl iletişime geçerim?', 'answer' => 'Telefonla'],
            ['question' => 'Neredesiniz?', 'answer' => 'İstanbul'],
        ]);
        $this->assertEquals('FAQPage', $faq['@type']);
        $this->assertCount(2, $faq['mainEntity']);
    }

    public function test_home_schema_returns_graph(): void
    {
        $settings = new \App\Models\SiteSetting(['site_name' => 'Test', 'address' => 'Adres']);
        $schema = SeoSchema::home($settings, null);
        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertIsArray($schema['@graph']);
        $this->assertGreaterThanOrEqual(2, count($schema['@graph']));
    }
}
