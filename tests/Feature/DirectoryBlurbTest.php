<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\SiteSetting;
use App\Support\DirectoryBlurb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryBlurbTest extends TestCase
{
    use RefreshDatabase;

    private function directory(string $name, string $domain, array $extra = []): Directory
    {
        return Directory::create($extra + [
            'name' => $name, 'slug' => str_replace('.', '-', $domain), 'domain' => $domain, 'status' => 'active',
        ]);
    }

    public function test_every_directory_gets_its_own_sentence_with_its_name(): void
    {
        $domains = [
            'Usta Harita' => 'ustaharita.com.tr', 'Esnaf Pusulası' => 'esnafpusulasi.com.tr', 'Kobiva' => 'kobiva.com.tr',
            'Kurumsal Bul' => 'kurumsalbul.com.tr', 'Hizmet Haritası' => 'hizmetharitasi.com.tr', 'İşletme Listesi' => 'isletmelistesi.com.tr',
            'Yerel Ustalar' => 'yerelustalar.com.tr', 'Kuaför Rehberi' => 'kuaforrehberi.com.tr', 'Firma Hub' => 'firmahub.com.tr',
        ];

        $texts = [];
        foreach ($domains as $name => $domain) {
            $directory = $this->directory($name, $domain);
            $text = DirectoryBlurb::footer($directory);

            $this->assertStringContainsString($name, $text, $domain);
            $this->assertStringNotContainsString('{', $text);
            $texts[] = $text;
        }

        $this->assertCount(count($domains), array_unique($texts), 'Her rehberin metni farklı olmalı.');
    }

    public function test_focus_follows_the_name_and_domain(): void
    {
        $this->assertStringContainsString('usta', mb_strtolower(DirectoryBlurb::footer($this->directory('Usta Harita', 'ustaharita.com.tr'))));
        $this->assertStringContainsString('esnaf', mb_strtolower(DirectoryBlurb::footer($this->directory('Esnaf Pusulası', 'esnafpusulasi.com.tr'))));
        $this->assertStringContainsString('kuaför', mb_strtolower(DirectoryBlurb::footer($this->directory('Kuaför Rehberi', 'kuaforrehberi.com.tr'))));
    }

    public function test_city_directories_mention_their_city_without_suffix_mistakes(): void
    {
        $local = $this->directory('Tekirdağ Firma Rehberi', 'tekirdagfirmarehberi.com.tr', ['geography_mode' => 'local', 'primary_city_slug' => 'tekirdag']);
        $custom = $this->directory('Trakya Firmalar', 'trakyafirmalar.com.tr', ['geography_mode' => 'custom', 'featured_city_slugs' => ['tekirdag', 'edirne', 'kirklareli']]);

        $this->assertStringContainsString('Tekirdağ ve çevresindeki', DirectoryBlurb::footer($local));
        $this->assertStringContainsString('Tekirdağ, Edirne ve Kırklareli bölgesindeki', DirectoryBlurb::footer($custom));
    }

    public function test_the_text_is_stable_and_written_descriptions_take_priority(): void
    {
        $directory = $this->directory('Kobiva', 'kobiva.com.tr');

        $this->assertSame(DirectoryBlurb::footer($directory), DirectoryBlurb::footer($directory));

        $settings = new SiteSetting(['meta_description' => 'Ayarlardan yazılan açıklama.']);
        $this->assertSame('Ayarlardan yazılan açıklama.', DirectoryBlurb::footer($directory, $settings));

        $directory->update(['meta_description' => 'Rehbere yazılan açıklama.']);
        $this->assertSame('Rehbere yazılan açıklama.', DirectoryBlurb::footer($directory, $settings));

        $this->assertSame(DirectoryBlurb::FALLBACK, DirectoryBlurb::footer(null));
    }

    public function test_generated_texts_never_mention_a_network_or_directory_counts(): void
    {
        foreach (['usta', 'esnaf', 'kobi', 'kurumsal', 'hizmet', 'isletme', 'yerel', 'kuafor', 'firma'] as $i => $focus) {
            $directory = $this->directory(ucfirst($focus).' Rehber', $focus.$i.'.com.tr');
            $text = mb_strtolower(DirectoryBlurb::footer($directory));

            $this->assertDoesNotMatchRegularExpression('/\d/', $text);
            $this->assertStringNotContainsString('rehberlerimiz', $text);
        }
    }

    public function test_footer_and_manifest_show_the_generated_sentence_instead_of_the_old_shared_one(): void
    {
        $directory = $this->directory('Usta Harita', 'ustaharita.test');
        $expected = DirectoryBlurb::footer($directory);

        $this->withServerVariables(['HTTP_HOST' => 'ustaharita.test'])->get('http://ustaharita.test/')
            ->assertOk()
            ->assertSee($expected)
            ->assertDontSee('Türkiye genelinde firma, kategori ve şehir araması için sade rehber deneyimi.');

        $this->withServerVariables(['HTTP_HOST' => 'ustaharita.test'])->get('http://ustaharita.test/site.webmanifest')
            ->assertOk()
            ->assertJsonPath('description', $expected);
    }
}
