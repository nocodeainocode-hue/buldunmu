<?php

namespace App\Support;

use App\Models\Directory;
use Illuminate\Support\Str;

/**
 * Ana sayfa başlığı (title), H1 ve meta açıklaması. Editörün bilinçli yazdığı metinler korunur; boş ya da
 * yalnızca marka adı olan alanlar için rehbere özgü, aranabilir metin üretilir. Her rehber odağına
 * (usta, esnaf, kurumsal, şehir…) ve alan adına göre farklı kalıp seçer, böylece siteler aynı başlığı taşımaz.
 */
class DirectorySeo
{
    public const TITLE_MAX = 60;
    public const DESCRIPTION_MIN = 120;
    public const DESCRIPTION_MAX = 155;
    /** Bu uzunluğun altındaki açıklama "bilinçli yazılmış" sayılmaz (ör. tek cümlelik slogan). */
    private const EXPLICIT_DESCRIPTION_MIN = 110;
    private const EXPLICIT_TITLE_MIN = 25;
    private const DESCRIPTION_TAIL = ' Sade, hızlı ve ücretsiz kullanım.';

    /** [ana ifade, ek ifade]; {yer} = Türkiye ya da şehir. */
    private const TITLES = [
        'firma' => [['{yer} Firma Rehberi', 'Şehre ve Kategoriye Göre Bulun'], ['{yer} Firmaları', 'Telefon, Adres ve Hizmet Bilgileri'], ['Firma Rehberi: {yer}', 'İşletme ve Hizmet Arama']],
        'usta' => [['{yer} Usta Rehberi', 'Hizmete Göre Usta Bulun'], ['Usta Bul ve Karşılaştır', 'Tesisat, Elektrik, Tadilat'], ['{yer} Ustaları', 'Şehre ve Hizmete Göre']],
        'esnaf' => [['{yer} Esnaf Rehberi', 'Mahalle ve Kategoriye Göre'], ['Esnaf Bul ve Keşfet', 'Dükkân ve Hizmet Rehberi'], ['{yer} Esnafı', 'Adres, Telefon ve Saatler']],
        'kobi' => [['{yer} KOBİ Rehberi', 'İşletmeleri Sektöre Göre Bulun'], ['KOBİ ve İşletme Rehberi', '{yer} Firmaları'], ['{yer} İşletme Rehberi', 'Sektör ve Şehre Göre']],
        'kurumsal' => [['{yer} Kurumsal Firma Rehberi', 'Tedarikçi Bulun'], ['Ticari Firma Rehberi', '{yer} Sektör ve Şehir'], ['Kurumsal Firma Bul', '{yer} Tedarikçi Rehberi']],
        'hizmet' => [['{yer} Hizmet Rehberi', 'Uygun Firmayı Bulun'], ['Hizmet Bul ve Karşılaştır', 'Kategori ve Şehre Göre'], ['{yer} Hizmet Sağlayıcıları', 'Telefon ve Konumla']],
        'isletme' => [['{yer} İşletme Rehberi', 'Kategori ve Şehre Göre'], ['İşletme Bul ve Karşılaştır', 'Adres, Telefon, Hizmetler'], ['{yer} İşletmeleri', 'Karşılaştırın, İletişime Geçin']],
        'yerel' => [['{yer} Yerel Firma Rehberi', 'Yakınınızdaki İşletmeler'], ['Yerel Firmalar ve Hizmetler', 'Şehir ve Kategoriye Göre'], ['Yakınımdaki Firmalar', '{yer} Yerel Rehber']],
        'kuafor' => [['{yer} Kuaför ve Güzellik Rehberi', 'Salonları Bulun'], ['Kuaför ve Güzellik Salonu Bul', 'Konuma Göre Rehber'], ['{yer} Güzellik Merkezleri', 'Kuaför Rehberi']],
    ];

    private const H1S = [
        'firma' => ['{yer} Firma Rehberi', 'Firmaları Şehre ve Kategoriye Göre Bulun', 'Aradığınız Firmayı Bulun: {yer} Rehberi'],
        'usta' => ['{yer} Usta Rehberi', 'İhtiyacınıza Uygun Ustayı Bulun', 'Usta Arıyorsanız: {yer} Usta Rehberi'],
        'esnaf' => ['{yer} Esnaf Rehberi', 'Yakınınızdaki Esnafı Keşfedin', 'Esnafı Mahalle ve Kategoriye Göre Bulun'],
        'kobi' => ['{yer} KOBİ ve İşletme Rehberi', 'İşletmeleri Sektöre ve Şehre Göre Bulun', 'KOBİ Arıyorsanız: İşletme Rehberi'],
        'kurumsal' => ['{yer} Kurumsal Firma Rehberi', 'Kurumsal Firmaları ve Tedarikçileri Bulun', 'Ticari Firma Rehberi: Sektöre Göre Arayın'],
        'hizmet' => ['{yer} Hizmet Rehberi', 'İhtiyacınız Olan Hizmeti Bulun', 'Hizmet Sağlayıcıları Şehre Göre Karşılaştırın'],
        'isletme' => ['{yer} İşletme Rehberi', 'Aradığınız İşletmeyi Bulun', 'İşletmeleri Kategori ve Şehre Göre Arayın'],
        'yerel' => ['{yer} Yerel Firma Rehberi', 'Yakınınızdaki Firmaları Bulun', 'Şehrinizdeki İşletmeleri Keşfedin'],
        'kuafor' => ['{yer} Kuaför ve Güzellik Rehberi', 'Size Yakın Kuaförü ve Salonu Bulun', 'Güzellik ve Bakım Salonlarını Keşfedin'],
    ];

    /** {ad}: rehber adı, {yer}: Türkiye / şehir / şehir listesi. */
    private const DESCRIPTIONS = [
        'firma' => [
            '{yer} firmalarını kategori ve şehre göre arayın. {ad} ile telefon, adres ve hizmet bilgilerine hızlıca ulaşın, doğrudan iletişime geçin.',
            '{ad}, {yer} işletmelerini tek yerde toplar: kategori seçin, şehri belirleyin, firmanın iletişim ve konum bilgilerini inceleyin.',
            'Aradığınız firmayı bulun: {yer} işletmelerini kategori ve şehir filtresiyle listeleyin, bilgilerini {ad} üzerinde karşılaştırın.',
        ],
        'usta' => [
            '{yer} ustalarını hizmet türüne ve şehre göre bulun. {ad} ile usta bilgilerini inceleyin, telefonla ya da WhatsApp ile hemen ulaşın.',
            'Tesisat, elektrik, tadilat ve daha fazlası: {yer} hizmet verenleri {ad} üzerinde karşılaştırın, doğru ustayı seçin.',
            '{ad}, usta aradığınızda {yer} seçeneklerini konuma ve hizmete göre sıralar; iletişim bilgilerine tek tıkla ulaşırsınız.',
        ],
        'esnaf' => [
            '{yer} esnafını mahalle, şehir ve kategoriye göre keşfedin. {ad} ile dükkân adreslerine ve telefon bilgilerine kolayca ulaşın.',
            'Yakınınızdaki esnafı bulmak için {ad}: {yer} dükkânları ve hizmetleri kategoriye göre listelenir, iletişim bilgileri açıktır.',
            '{ad}, {yer} esnafının adres, telefon ve çalışma bilgilerini tek yerde toplar. Aradığınız esnafı birkaç tıklamayla bulun.',
        ],
        'kobi' => [
            '{yer} küçük ve orta ölçekli işletmeleri sektöre ve şehre göre inceleyin. {ad} ile firmaların hizmet ve iletişim bilgilerine ulaşın.',
            'KOBİ arıyorsanız {ad}: {yer} işletmelerini kategori ve şehir filtresiyle listeleyin, hizmetlerini karşılaştırın, iletişime geçin.',
            '{ad} ile {yer} işletmelerini tanıyın; sektörünüze uygun firmayı bulun ve telefon ya da WhatsApp ile doğrudan görüşün.',
        ],
        'kurumsal' => [
            '{yer} kurumsal firmaları ve tedarikçileri sektöre ve şehre göre bulun. {ad} ile iş ortaklarınızı karşılaştırın, iletişime geçin.',
            'İş ortağı ya da tedarikçi mi arıyorsunuz? {ad} üzerinde {yer} ticari firmaları kategori ve şehir bazında inceleyin.',
            '{ad}, {yer} profesyonel hizmet sağlayıcılara ve ticari işletmelere kolay erişim sunar: sektör seçin, firmayı bulun, ulaşın.',
        ],
        'hizmet' => [
            'İhtiyacınız olan hizmeti bulun: {yer} hizmet sağlayıcıları {ad} üzerinde kategori ve şehre göre listelenir, iletişim bilgileri açıktır.',
            '{ad} ile {yer} firmalarını hizmet türüne göre arayın, bilgilerini karşılaştırın ve doğrudan iletişime geçin.',
            'Hangi hizmete ihtiyacınız olursa olsun, {yer} seçenekleri {ad} üzerinde: kategori seçin, firmayı inceleyin, hemen arayın.',
        ],
        'isletme' => [
            '{yer} işletmelerini kategoriye ve şehre göre listeleyin. {ad} ile işletmelerin adres, telefon ve hizmet bilgilerini karşılaştırın.',
            '{ad}: aradığınız işletmeye ulaşın. {yer} firmaları kategori ve şehir araması ile bulunur, iletişim bilgileri tek sayfada görünür.',
            'Güncel işletme bilgilerini {ad} üzerinde inceleyin; {yer} işletmelerini kategori, şehir ve hizmete göre arayıp doğrudan görüşün.',
        ],
        'yerel' => [
            'Yakınınızdaki firmaları bulun: {ad} ile {yer} yerel işletmeleri kategori ve şehir filtresiyle listeleyin, bilgilerini inceleyin.',
            '{ad}, {yer} yerel firmaları tek yerde toplayan sade bir rehberdir. Hizmet seçin, firmayı bulun, telefonla ya da WhatsApp ile ulaşın.',
            'Şehrinizdeki işletmeleri {ad} ile arayın: kategori seçin, adres ve telefon bilgilerine bakın, doğrudan iletişime geçin.',
        ],
        'kuafor' => [
            '{yer} kuaför ve güzellik salonlarını şehre göre bulun. {ad} ile salonların hizmet, adres ve iletişim bilgilerine ulaşın.',
            'Size yakın salon mu arıyorsunuz? {ad} ile {yer} kuaförlerini ve güzellik merkezlerini listeleyin, hemen iletişime geçin.',
            'Kuaför, güzellik ve bakım hizmetleri: {yer} salonları {ad} üzerinde konuma göre listelenir, telefon bilgilerine kolayca ulaşılır.',
        ],
    ];

    /**
     * @return array{title: string, h1: string, description: string, title_generated: bool, description_generated: bool}
     */
    public static function home(Directory $directory, ?object $settings = null): array
    {
        $titleGenerated = ! self::hasExplicitTitle($directory, $settings);
        $descriptionExplicit = self::explicitDescription($directory, $settings);

        return [
            'title' => $titleGenerated ? self::title($directory) : trim((string) $settings->homepage_title),
            'h1' => $titleGenerated ? self::h1($directory) : trim((string) $settings->homepage_title),
            'description' => $descriptionExplicit ?? self::description($directory),
            'title_generated' => $titleGenerated,
            'description_generated' => $descriptionExplicit === null,
        ];
    }

    public static function title(Directory $directory): string
    {
        [$main, $extra] = self::pick(self::TITLES[DirectoryBlurb::focus($directory)], $directory, 0);
        $place = self::place($directory, false);
        $main = str_replace('{yer}', $place, $main);
        $extra = str_replace('{yer}', $place, $extra);
        $brand = trim((string) $directory->name);

        foreach ([
            "{$main} | {$extra} - {$brand}",
            "{$main} - {$brand}",
            "{$main} | {$extra}",
            $main,
        ] as $candidate) {
            if (mb_strlen($candidate) <= self::TITLE_MAX) {
                return $candidate;
            }
        }

        return Str::limit($main, self::TITLE_MAX, '');
    }

    public static function h1(Directory $directory): string
    {
        return str_replace('{yer}', self::place($directory, false), self::pick(self::H1S[DirectoryBlurb::focus($directory)], $directory, 3));
    }

    public static function description(Directory $directory): string
    {
        $text = strtr(self::pick(self::DESCRIPTIONS[DirectoryBlurb::focus($directory)], $directory, 6), [
            '{ad}' => trim((string) $directory->name),
            '{yer}' => self::place($directory, true),
        ]);

        if (mb_strlen($text) < self::DESCRIPTION_MIN) {
            $text .= self::DESCRIPTION_TAIL;
        }

        if (mb_strlen($text) > self::DESCRIPTION_MAX) {
            $cut = mb_substr($text, 0, self::DESCRIPTION_MAX);
            $space = mb_strrpos($cut, ' ');
            $text = rtrim($space === false ? $cut : mb_substr($cut, 0, $space), " ,;:-").'.';
        }

        return $text;
    }

    public static function ogImage(Directory $directory, ?object $settings = null): ?string
    {
        foreach ([$directory->hero_image, $directory->logo, $settings?->logo ?? null] as $path) {
            if (filled($path)) {
                return str_starts_with($path, 'http') ? $path : asset('storage/'.ltrim($path, '/'));
            }
        }

        return null;
    }

    private static function hasExplicitTitle(Directory $directory, ?object $settings): bool
    {
        $title = trim((string) ($settings->homepage_title ?? ''));

        if ($title === '' || mb_strlen($title) < self::EXPLICIT_TITLE_MIN) {
            return false;
        }

        // Rehber oluşturulurken yazılan varsayılan başlıklar bilinçli sayılmaz.
        $name = Str::lower(Str::ascii(trim((string) $directory->name)));
        $plain = Str::lower(Str::ascii($title));

        return $plain !== $name && ! str_ends_with($plain, ' - aradiginiz firmayi bulun');
    }

    private static function explicitDescription(Directory $directory, ?object $settings): ?string
    {
        foreach ([$directory->meta_description, $settings->meta_description ?? null] as $text) {
            if (filled($text) && mb_strlen(trim((string) $text)) >= self::EXPLICIT_DESCRIPTION_MIN) {
                return trim((string) $text);
            }
        }

        return null;
    }

    private static function place(Directory $directory, bool $long): string
    {
        $labels = TurkeyCities::options();
        $names = collect($directory->visibleCitySlugs())->map(fn (string $slug) => $labels[$slug] ?? null)->filter()->values();

        if ($directory->geography_mode === 'local' && $names->isNotEmpty()) {
            return $names->first();
        }

        if ($directory->geography_mode === 'custom' && $names->isNotEmpty()) {
            if (! $long) {
                return $names->count() > 2 ? $names->first().' ve Çevresi' : $names->implode(' ve ');
            }

            $last = $names->pop();

            return $names->isEmpty() ? $last : $names->implode(', ').' ve '.$last;
        }

        return 'Türkiye';
    }

    /** Alan adından sabit bir şekilde kalıp seçer; $shift farklı alanların farklı kalıp seçmesini sağlar. */
    private static function pick(array $options, Directory $directory, int $shift): mixed
    {
        return $options[((crc32((string) $directory->domain.$directory->id) >> $shift) & 0xFFFF) % count($options)];
    }
}
