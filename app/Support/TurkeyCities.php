<?php

namespace App\Support;

use Illuminate\Support\Str;

class TurkeyCities
{
    public const NAMES = [
        'Adana', 'Adiyaman', 'Afyonkarahisar', 'Agri', 'Amasya', 'Ankara', 'Antalya', 'Artvin',
        'Aydin', 'Balikesir', 'Bilecik', 'Bingol', 'Bitlis', 'Bolu', 'Burdur', 'Bursa',
        'Canakkale', 'Cankiri', 'Corum', 'Denizli', 'Diyarbakir', 'Edirne', 'Elazig', 'Erzincan',
        'Erzurum', 'Eskisehir', 'Gaziantep', 'Giresun', 'Gumushane', 'Hakkari', 'Hatay', 'Isparta',
        'Mersin', 'Istanbul', 'Izmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kirklareli', 'Kirsehir',
        'Kocaeli', 'Konya', 'Kutahya', 'Malatya', 'Manisa', 'Kahramanmaras', 'Mardin', 'Mugla',
        'Mus', 'Nevsehir', 'Nigde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop',
        'Sivas', 'Tekirdag', 'Tokat', 'Trabzon', 'Tunceli', 'Sanliurfa', 'Usak', 'Van',
        'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman', 'Kirikkale', 'Batman', 'Sirnak',
        'Bartin', 'Ardahan', 'Igdir', 'Yalova', 'Karabuk', 'Kilis', 'Osmaniye', 'Duzce',
    ];

    private const LABELS = [
        'adiyaman' => 'Adıyaman', 'agri' => 'Ağrı', 'aydin' => 'Aydın', 'balikesir' => 'Balıkesir',
        'bingol' => 'Bingöl', 'canakkale' => 'Çanakkale', 'cankiri' => 'Çankırı', 'corum' => 'Çorum',
        'diyarbakir' => 'Diyarbakır', 'elazig' => 'Elazığ', 'eskisehir' => 'Eskişehir',
        'gumushane' => 'Gümüşhane', 'istanbul' => 'İstanbul', 'izmir' => 'İzmir',
        'kirklareli' => 'Kırklareli', 'kirsehir' => 'Kırşehir', 'kutahya' => 'Kütahya',
        'kahramanmaras' => 'Kahramanmaraş', 'mugla' => 'Muğla', 'mus' => 'Muş',
        'nevsehir' => 'Nevşehir', 'nigde' => 'Niğde', 'sanliurfa' => 'Şanlıurfa',
        'tekirdag' => 'Tekirdağ', 'usak' => 'Uşak', 'kirikkale' => 'Kırıkkale',
        'sirnak' => 'Şırnak', 'bartin' => 'Bartın', 'igdir' => 'Iğdır', 'karabuk' => 'Karabük',
        'duzce' => 'Düzce',
    ];

    public static function options(): array
    {
        return collect(self::NAMES)->mapWithKeys(function (string $name) {
            $slug = Str::slug($name);

            return [$slug => self::LABELS[$slug] ?? $name];
        })->all();
    }
}
