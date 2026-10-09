<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Directory;

/**
 * Firmanın elindeki gerçek verilerden (ad, kategori, şehir, ilçe, adres, telefon)
 * kısa açıklama ve metin üretir. Aynı firma farklı rehberlerde farklı cümle
 * kalıplarıyla yazılır (seed = rehber + firma), böylece rehberler arası kopya
 * içerik oluşmaz. Metin yalnızca bilinen bilgileri söyler; hizmet, deneyim veya
 * kalite iddiası UYDURMAZ.
 */
class CompanyDescriptionGenerator
{
    /** Kategori -> "bir ... işletmesi" cümlesinde kullanılan tür adı. */
    private const KINDS = [
        'Otomotiv' => 'otomotiv işletmesi',
        'Restaurant ve Lokantalar' => 'restoran ve lokanta işletmesi',
        'Mobilya' => 'mobilya işletmesi',
        'Güzellik ve Kişisel Bakım' => 'güzellik ve kişisel bakım işletmesi',
        'Hizmet Sektörü' => 'yerel hizmet işletmesi',
        'Emlak ve Gayrimenkul' => 'emlak ve gayrimenkul işletmesi',
        'Temizlik Hizmetleri' => 'temizlik hizmetleri işletmesi',
        'Eğitim' => 'eğitim kurumu',
        'Sağlık' => 'sağlık kuruluşu',
        'İklimlendirme' => 'iklimlendirme işletmesi',
        'Veteriner ve Evcil Hayvan' => 'veteriner ve evcil hayvan işletmesi',
        'Turizm ve Seyahat' => 'turizm ve seyahat işletmesi',
        'Enerji ve Yakıt' => 'enerji ve yakıt işletmesi',
        'Elektrik' => 'elektrik işletmesi',
        'Market ve Perakende' => 'market ve perakende işletmesi',
        'Hukuk ve Danışmanlık' => 'hukuk ve danışmanlık işletmesi',
        'Giyim' => 'giyim işletmesi',
        'Gıda' => 'gıda işletmesi',
        'Spor' => 'spor işletmesi',
        'Nakliye ve Lojistik' => 'nakliye ve lojistik işletmesi',
        'İnşaat ve Yapı Dekorasyon' => 'inşaat ve yapı dekorasyon işletmesi',
        'Bilgisayar ve Bilişim' => 'bilgisayar ve bilişim işletmesi',
        'Elektronik' => 'elektronik işletmesi',
        'Güvenlik Hizmetleri' => 'güvenlik hizmetleri işletmesi',
        'Makine' => 'makine işletmesi',
        'Reklam ve Organizasyon' => 'reklam ve organizasyon işletmesi',
    ];

    /** @return array{short: string, html: string} */
    public function generate(Company $company, Directory $directory): array
    {
        $category = (string) ($company->category?->name ?: 'Hizmet Sektörü');
        $city = (string) ($company->city?->name ?: '');
        $district = (string) ($company->district?->name ?: '');
        $name = trim((string) $company->name);
        $directoryName = trim((string) $directory->name);

        $place = $city !== '' ? ($district !== '' ? "{$city} / {$district}" : $city) : 'Türkiye';
        $areaLoc = $this->locative($district !== '' ? $district : ($city !== '' ? $city : 'Türkiye'));
        $areaKi = $areaLoc.'ki';
        $cityKi = $this->locative($city !== '' ? $city : 'Türkiye').'ki';
        $kind = self::KINDS[$category] ?? mb_strtolower(strtr($category, ['I' => 'ı', 'İ' => 'i'])).' işletmesi';
        $field = mb_strtolower(strtr($category, ['I' => 'ı', 'İ' => 'i']));
        $phone = $this->formatPhone($company->phone);
        $address = trim((string) $company->address);

        $seed = crc32($directory->id.'|'.$company->id.'|'.$name);

        $vars = compact('name', 'category', 'city', 'district', 'place', 'areaLoc', 'areaKi', 'cityKi', 'kind', 'field', 'phone', 'address', 'directoryName');

        $short = $this->fill($this->pick([
            '{name}, {place} bölgesinde {category} kategorisinde listelenen bir işletmedir.',
            '{areaLoc} {field} arayanlar için {name} işletmesinin adres ve iletişim bilgileri.',
            '{name} · {category} · {place}. Telefon ve adres bilgilerine bu sayfadan ulaşabilirsiniz.',
            '{name}, {category} kategorisinde {place} konumunda yer alan bir {kind}.',
            '{areaKi} {field} işletmeleri arasında {name} de listeleniyor.',
            '{name} için {place} adresi, telefon numarası ve harita bilgileri bu profilde.',
            '{directoryName} rehberinde {name} ({category}, {place}) için güncel iletişim bilgileri yer alıyor.',
            '{place} çevresinde {kind} arıyorsanız {name} profilini inceleyin.',
            '{name}: {place} konumlu {kind}. Yol tarifi ve telefon bilgisi burada.',
            '{category} alanında {place} bölgesindeki {name} işletmesine bu sayfadan ulaşın.',
            '{name} işletmesinin {place} adres ve iletişim bilgileri {directoryName} rehberinde.',
            '{cityKi} {kind} listesinde {name} yer alıyor; adres ve telefon bilgileri aşağıda.',
            '{name} ({category}) işletmesine ait {place} adresi ve telefon bilgisi {directoryName} rehberinde.',
            '{areaLoc} {kind} arıyorsanız {name} işletmesinin iletişim bilgileri bu sayfada.',
            '{place} konumundaki {name}, {directoryName} rehberinde {category} kategorisinde yer alıyor.',
            '{name} için telefon, adres ve harita bilgileri: {place}, {category}.',
            '{category} kategorisinde listelenen {name}, {place} bölgesinden ulaşılabilecek bir işletmedir.',
            '{directoryName} üzerinde {place} bölgesindeki {name} işletmesinin kaydı ve iletişim bilgileri.',
            '{name} işletmesi {place} adresinde yer alır; {category} alanındaki rehber kaydını inceleyebilirsiniz.',
            '{areaKi} {kind} {name} için iletişim ve konum bilgilerine bu profilden ulaşabilirsiniz.',
        ], $seed, 1), $vars);

        $paragraphs = [];

        $paragraphs[] = $this->fill($this->pick([
            '{name}, {place} bölgesinde faaliyet gösteren bir {kind} olarak {directoryName} rehberinde listelenmektedir. Bu sayfada işletmenin kategori, konum ve iletişim bilgilerini bulabilirsiniz.',
            '{directoryName} rehberindeki {name} profili, {place} konumundaki {kind} hakkında temel bilgileri bir araya getirir: kategori, adres ve telefon numarası.',
            '{category} kategorisinde yer alan {name}, {place} adresinde bulunmaktadır. Aşağıdaki bilgiler işletmenin rehberdeki kaydına göre derlenmiştir.',
            '{areaLoc} {field} işletmesi arayanlar {directoryName} rehberinde {name} kaydıyla karşılaşır. Profil; adres, telefon ve harita bilgilerini içerir.',
            '{name}, {directoryName} rehberine {category} kategorisinde kayıtlı bir işletmedir ve {place} bölgesinde yer alır. Güncel bilgiler işletme tarafından değiştirilebilir.',
            '{place} bölgesindeki {name} işletmesi, rehberimizde {category} kategorisi altında gösterilir. İşletmeyle ilgili iletişim ve konum detayları aşağıda yer alır.',
        ], $seed, 2), $vars);

        $contact = $address !== ''
            ? [
                'Adres bilgisi: {address}. Bilgi almak için {phone} numaralı telefondan işletmeyle iletişime geçebilirsiniz.',
                'İşletmenin kayıtlı adresi {address} şeklindedir. Telefonla ulaşmak için {phone} numarasını arayabilirsiniz.',
                '{phone} numarasını arayarak {name} ile iletişime geçebilir, adres olarak {address} bilgisini kullanabilirsiniz.',
                'Ziyaret etmeden önce {phone} numarasından arayıp bilgi alabilirsiniz. Kayıtlı adres: {address}.',
                'Konum: {address}. İletişim: {phone}. Ziyaretten önce çalışma saatlerini işletmeden teyit etmeniz önerilir.',
                'Haritada konumu görmek için adres bilgisinden yararlanabilirsiniz ({address}). Telefon: {phone}.',
            ]
            : [
                'Bilgi almak için {phone} numaralı telefondan {name} ile iletişime geçebilirsiniz.',
                'İşletmeye {phone} numarasından ulaşabilirsiniz. Ziyaretten önce çalışma saatlerini teyit etmeniz önerilir.',
                '{name} için kayıtlı telefon numarası {phone} şeklindedir; ayrıntılı bilgi için arayabilirsiniz.',
            ];
        $paragraphs[] = $this->fill($this->pick($contact, $seed, 3), $vars);

        $paragraphs[] = $this->fill($this->pick([
            '{category} alanında bir işletme seçerken fiyat, çalışma saatleri ve hizmet kapsamını işletmeden doğrudan teyit etmek en güvenli yoldur.',
            'Bir {kind} ile çalışmadan önce güncel fiyat ve randevu bilgisini telefonla sorarak netleştirmenizi öneririz.',
            'Bu sayfadaki bilgiler rehber kaydına dayanır; güncel çalışma günleri ve hizmet detayları için işletmeyle iletişime geçmek faydalı olur.',
            '{place} bölgesinde {field} hizmeti ararken birkaç işletmeyi karşılaştırmak, ihtiyacınıza en uygun seçeneği bulmanıza yardımcı olur.',
            'İşletme sahibiyseniz profili sahiplenip çalışma saatleri, hizmetler ve görseller gibi bilgileri güncelleyebilirsiniz.',
            'Bilgilerde eksik ya da hata görürseniz işletme sahibi profili sahiplenerek düzeltebilir; böylece ziyaretçiler en güncel bilgiye ulaşır.',
        ], $seed, 4), $vars);

        if ($seed % 2 === 0) {
            $paragraphs[] = $this->fill($this->pick([
                '{directoryName} rehberinde {cityKi} diğer {field} işletmelerine kategori sayfasından ulaşabilirsiniz.',
                '{city} şehrindeki işletmeleri görmek için {directoryName} rehberinin şehir sayfasını inceleyebilirsiniz.',
                '{category} kategorisindeki diğer işletmeleri karşılaştırmak için rehberin kategori sayfasına göz atabilirsiniz.',
            ], $seed, 5), $vars);
        }

        $html = collect($paragraphs)
            ->map(fn (string $p) => '<p>'.e($p).'</p>')
            ->implode("\n");

        return ['short' => $short, 'html' => $html];
    }

    /** Türkçe bulunma eki: Bursa'da, Nilüfer'de, Kars'ta, Beşiktaş'ta. */
    public function locative(string $word): string
    {
        $word = trim($word);
        if ($word === '') {
            return $word;
        }

        $lower = mb_strtolower(strtr($word, ['I' => 'ı', 'İ' => 'i']));
        $lastVowel = null;
        foreach (array_reverse(mb_str_split($lower)) as $char) {
            if (mb_strpos('aıoueiöü', $char) !== false) {
                $lastVowel = $char;
                break;
            }
        }

        $back = $lastVowel !== null && mb_strpos('aıou', $lastVowel) !== false;
        $voiceless = mb_strpos('çfhkpsşt', mb_substr($lower, -1)) !== false;

        return $word."'".($voiceless ? 't' : 'd').($back ? 'a' : 'e');
    }

    private function formatPhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (strlen($digits) === 11 && $digits[0] === '0') {
            return sprintf('%s %s %s %s', substr($digits, 0, 4), substr($digits, 4, 3), substr($digits, 7, 2), substr($digits, 9, 2));
        }

        return trim((string) $phone);
    }

    /** @param array<int, string> $options */
    private function pick(array $options, int $seed, int $salt): string
    {
        return $options[crc32($seed.'|'.$salt) % count($options)];
    }

    /** @param array<string, string> $vars */
    private function fill(string $template, array $vars): string
    {
        $text = preg_replace_callback('/\{(\w+)\}/', fn (array $m) => $vars[$m[1]] ?? '', $template) ?? $template;

        return trim(preg_replace('/\s{2,}/u', ' ', $text) ?? $text);
    }
}
