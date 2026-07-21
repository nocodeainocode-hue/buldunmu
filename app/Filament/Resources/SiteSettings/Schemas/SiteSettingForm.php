<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site Bilgileri')
                    ->schema([
                        TextInput::make('site_name')
                            ->required(),
                        TextInput::make('logo'),
                        TextInput::make('favicon'),
                        TextInput::make('phone')
                            ->tel(),
                        TextInput::make('whatsapp'),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email(),
                        Textarea::make('address')
                            ->columnSpanFull(),
                        TextInput::make('homepage_title'),
                        TextInput::make('homepage_subtitle'),
                        TextInput::make('meta_title'),
                        TextInput::make('meta_description'),
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
