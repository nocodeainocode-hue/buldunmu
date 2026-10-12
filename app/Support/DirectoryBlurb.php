<?php

namespace App\Support;

use App\Models\Directory;
use Illuminate\Support\Str;

/**
 * Footer ve uygulama açıklaması. Editörün yazdığı açıklama (rehber ya da site ayarı) her zaman önceliklidir;
 * yoksa rehberin adından, odağından (şehir/sektör) ve alan adından sabit bir şekilde seçilen, rehbere özgü
 * bir cümle üretilir. Böylece rehberlerin hepsinde aynı metin görünmez.
 */
class DirectoryBlurb
{
    public const FALLBACK = 'Firma, kategori ve şehir araması için sade rehber deneyimi.';

    /** {ad}: rehber adı, {konum}: "Türkiye genelindeki" / "Tekirdağ ve çevresindeki" gibi sıfat öbeği. */
    private const TEMPLATES = [
        'usta' => [
            '{ad}: {konum} usta ve hizmet sağlayıcıları kategoriye göre bulun, bilgilerini inceleyin ve doğrudan iletişime geçin.',
            '{konum} ustaları hizmet türüne ve konuma göre karşılaştırın. {ad}, ihtiyacınıza uygun ustayı bulmanız için hazırlandı.',
            'Tesisattan tadilata kadar {konum} hizmet verenleri {ad} üzerinden arayın, telefonla ya da WhatsApp ile ulaşın.',
            '{ad} ile {konum} ustaları kategoriye ve konuma göre listeleyin, işi emanet edeceğiniz kişiyi seçin.',
        ],
        'esnaf' => [
            '{ad}: {konum} esnafı mahalle, şehir ve kategoriye göre keşfedin.',
            'Mahalledeki dükkândan şehirdeki ustaya kadar {konum} esnafı {ad} üzerinde bulun.',
            '{ad}, {konum} esnafın adres, telefon ve çalışma bilgilerini tek yerde toplar.',
            'Yakınınızdaki esnafı ve hizmetleri bulmak için {ad}: sade, hızlı ve ücretsiz bir rehber.',
        ],
        'kobi' => [
            '{ad}: {konum} küçük ve orta ölçekli işletmeleri sektöre ve şehre göre inceleyin.',
            '{konum} işletmeleri tanıyın, hizmetlerini karşılaştırın ve doğrudan iletişime geçin. {ad} sade bir işletme rehberidir.',
            '{ad}, KOBİ arayanlara {konum} firmaların iletişim ve hizmet bilgilerini kolayca sunar.',
            'Sektörünüze uygun işletmeyi {ad} ile bulun; {konum} firmalar kategori ve şehir filtresiyle listelenir.',
        ],
        'kurumsal' => [
            '{ad}: {konum} kurumsal firmaları ve tedarikçileri sektöre ve şehre göre bulun.',
            'İş ortağı ve tedarikçi ararken {konum} firmaları {ad} üzerinde karşılaştırın.',
            '{ad}, {konum} profesyonel hizmet sağlayıcılara ve ticari işletmelere kolay erişim sunar.',
            'Ticari firmaları sektör, şehir ve hizmet türüne göre {ad} ile arayın.',
        ],
        'hizmet' => [
            '{ad}: ihtiyacınız olan hizmeti bulun, {konum} hizmet sağlayıcıları inceleyin ve doğrudan iletişime geçin.',
            '{konum} hizmet sağlayıcılar tek yerde: {ad} ile kategori ve şehre göre arayın.',
            '{ad}, hizmet arayanlarla {konum} işletmeleri buluşturan sade bir rehberdir.',
            'Hangi hizmete ihtiyacınız olursa olsun, {konum} seçenekleri {ad} üzerinde görün.',
        ],
        'isletme' => [
            '{ad}: {konum} işletmeleri kategoriye ve şehre göre listeleyin, bilgilerini karşılaştırın.',
            '{konum} işletmelerin adres, telefon ve hizmet bilgilerini {ad} üzerinde bulun.',
            '{ad} ile aradığınız işletmeye ulaşın; {konum} firmalar kategori ve şehir araması ile kolayca bulunur.',
            'Güncel işletme bilgilerini {ad} üzerinde inceleyin; sade, hızlı ve kullanımı kolay bir rehber.',
        ],
        'yerel' => [
            '{ad}: {konum} yerel işletmeleri ve hizmetleri şehrinize göre keşfedin.',
            'Yakınınızdaki firmaları bulmak için {ad}; {konum} işletmeler kategori ve şehir filtresiyle listelenir.',
            '{ad}, {konum} yerel firmaları tek yerde toplayan sade bir rehberdir.',
            'Şehrinizdeki işletmeleri {ad} ile arayın, bilgilerini inceleyin ve iletişime geçin.',
        ],
        'kuafor' => [
            '{ad}: {konum} kuaför ve güzellik salonlarını şehre göre bulun, bilgilerini inceleyin.',
            'Kuaför, güzellik ve bakım hizmetlerini {konum} seçenekleriyle {ad} üzerinde karşılaştırın.',
            '{ad}, {konum} kuaför ve bakım salonlarının iletişim bilgilerini bir arada sunar.',
            'Size yakın bir salon mu arıyorsunuz? {ad} ile şehrinizdeki kuaförleri ve güzellik merkezlerini bulun.',
        ],
        'firma' => [
            '{ad}: {konum} firmaları kategori ve şehre göre arayın, bilgilerini inceleyin.',
            '{konum} firmaları {ad} üzerinde bulun; sade ve kolay kullanımlı bir rehber.',
            '{ad}, aradığınız firmaya ulaşmanız için {konum} işletmeleri şehir ve kategoriyle listeler.',
            'Firma, kategori ve şehir araması için {ad}: telefon ve konum bilgilerine hızlıca ulaşın.',
        ],
    ];

    /** Alan adı ve addaki anahtar kelimelere göre odak (sıra önemli: ilk eşleşen kazanır). */
    private const FOCUS_KEYWORDS = [
        'kuafor' => ['kuafor', 'guzellik'],
        'usta' => ['usta'],
        'esnaf' => ['esnaf'],
        'kobi' => ['kobi'],
        'kurumsal' => ['kurumsal', 'ticaret', 'tedarik', 'sektor', 'vip', 'prestij'],
        'hizmet' => ['hizmet', 'service'],
        'isletme' => ['isletme'],
        'yerel' => ['yerel', 'kent', 'sehir', 'komsu', 'mahalle', 'local', 'yakin'],
    ];

    public static function footer(?Directory $directory, ?object $settings = null): string
    {
        foreach ([$directory?->meta_description, $settings?->meta_description ?? null] as $written) {
            if (filled($written)) {
                return (string) $written;
            }
        }

        return $directory ? self::generate($directory) : self::FALLBACK;
    }

    public static function generate(Directory $directory): string
    {
        $focus = self::focus($directory);
        $templates = self::TEMPLATES[$focus];
        $template = $templates[crc32((string) $directory->domain.$directory->id) % count($templates)];

        return strtr($template, [
            '{ad}' => (string) $directory->name,
            '{konum}' => self::location($directory),
        ]);
    }

    public static function focus(Directory $directory): string
    {
        $haystack = Str::of(($directory->domain ?? '').' '.$directory->name)->ascii()->lower()->toString();

        foreach (self::FOCUS_KEYWORDS as $focus => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    return $focus;
                }
            }
        }

        return 'firma';
    }

    private static function location(Directory $directory): string
    {
        $labels = TurkeyCities::options();
        $names = collect($directory->visibleCitySlugs())
            ->map(fn (string $slug) => $labels[$slug] ?? null)
            ->filter()
            ->values();

        if ($directory->geography_mode === 'local' && $names->isNotEmpty()) {
            return $names->first().' ve çevresindeki';
        }

        if ($directory->geography_mode === 'custom' && $names->isNotEmpty()) {
            $last = $names->pop();

            return ($names->isEmpty() ? $last : $names->implode(', ').' ve '.$last).' bölgesindeki';
        }

        return 'Türkiye genelindeki';
    }
}
