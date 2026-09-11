<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site Bilgileri')
                    ->description('Site adı, logo, favicon, domain, tema ve genel SEO bilgileri Rehberler > Düzenle ekranından yönetilir.')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel(),
                        TextInput::make('whatsapp')->label('WhatsApp'),
                        TextInput::make('email')
                            ->label('E-posta')
                            ->email(),
                        Textarea::make('address')->label('Adres')
                            ->columnSpanFull(),
                        TextInput::make('homepage_title')->label('Ana Sayfa Başlığı'),
                        TextInput::make('homepage_subtitle')->label('Ana Sayfa Alt Başlığı'),
                    ]),
                Section::make('Özellik Ayarları')
                    ->schema([
                        Toggle::make('show_membership_plans')
                            ->label('Üyelik paketleri gösterilsin mi?')
                            ->helperText('Aktif edildiğinde ön yüzde üyelik paketleri listelenir.')
                            ->default(false),
                    ]),
            ]);
    }
}
