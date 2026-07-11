<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Firma Bilgileri')
                    ->description('Firmanın temel bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Firma Adı')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set) =>
                                        $set('slug', Str::slug($state))
                                    ),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('city_id')
                                    ->label('Şehir')
                                    ->relationship('city', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live(),
                                Select::make('district_id')
                                    ->label('İlçe')
                                    ->relationship('district', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->tel(),
                                TextInput::make('whatsapp')
                                    ->label('WhatsApp'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('E-posta')
                                    ->email(),
                                TextInput::make('website')
                                    ->label('Web Sitesi')
                                    ->url(),
                            ]),
                        Textarea::make('address')
                            ->label('Adres')
                            ->columnSpanFull(),
                        Textarea::make('google_maps_url')
                            ->label('Google Maps iframe kodu veya konum linki')
                            ->helperText('Google Maps embed iframe kodunu ya da maps URL\'sini yapıştırın. Kaydedince enlem/boylam otomatik çıkarılır.')
                            ->rows(4)
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Enlem (Latitude)')
                                    ->numeric()
                                    ->helperText('Google Maps iframe kodundan otomatik doldurulur.'),
                                TextInput::make('longitude')
                                    ->label('Boylam (Longitude)')
                                    ->numeric()
                                    ->helperText('Google Maps iframe kodundan otomatik doldurulur.'),
                            ]),
                        Textarea::make('opening_hours')
                            ->label('Çalışma Saatleri')
                            ->helperText('Her satıra bir gün: "Pazartesi: 09:00 - 18:00"')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull(),
                    ]),

                Section::make('Görseller')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('companies/logos')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300'),
                        FileUpload::make('cover_image')
                            ->label('Kapak Görseli')
                            ->image()
                            ->disk('public')
                            ->directory('companies/covers')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('400'),
                    ]),

                Section::make('Fotoğraf Galerisi')
                    ->description('Sürükle-bırak ile çoklu fotoğraf yükleyin ve sıralayın (max 20 adet, her biri max 5MB, jpg/png/webp)')
                    ->collapsible()
                    ->schema([
                        Repeater::make('gallery_images')
                            ->label('Galeri Fotoğrafları')
                            ->relationship('images')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Fotoğraf')
                                    ->image()
                                    ->disk('public')
                                    ->directory('firmalar/galeri')
                                    ->maxSize(5120)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('900')
                                    ->imagePreviewHeight('200')
                                    ->required(),
                                TextInput::make('alt_text')
                                    ->label('Alternatif Metin (SEO)')
                                    ->helperText('Görsel için açıklayıcı kısa metin')
                                    ->maxLength(255),
                            ])
                            ->maxItems(20)
                            ->reorderable()
                            ->reorderableWithDragAndDrop()
                            ->orderColumn('sort_order')
                            ->defaultItems(0)
                            ->addActionLabel('Fotoğraf Ekle')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => !empty($state['image_path']) ? basename($state['image_path']) : 'Yeni Fotoğraf')
                            ->grid(2),
                    ]),

                Section::make('Hizmetler ve İletişim Kanalları')
                    ->description('Firma detay sayfasında gösterilecek hizmet maddeleri')
                    ->collapsible()
                    ->schema([
                        Repeater::make('services')
                            ->label('Hizmet Maddeleri')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Hizmet')
                                    ->required()
                                    ->placeholder('örn: Su Arıtma Cihazı Montajı'),
                            ])
                            ->defaultItems(3)
                            ->addActionLabel('Hizmet Ekle')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'Yeni Hizmet')
                            ->grid(2),
                    ]),

                Section::make('Neden Bu Firma?')
                    ->description('Firma detay sayfasında gösterilecek öne çıkan özellikler')
                    ->collapsible()
                    ->schema([
                        Repeater::make('why_us_items')
                            ->label('Öne Çıkan Özellikler')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->required()
                                    ->placeholder('örn: Kolay İletişim'),
                                Textarea::make('description')
                                    ->label('Açıklama')
                                    ->required()
                                    ->placeholder('Telefon, WhatsApp ve e-posta ile hızlı ulaşım.')
                                    ->rows(2),
                            ])
                            ->defaultItems(3)
                            ->addActionLabel('Özellik Ekle')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'Yeni Özellik')
                            ->grid(1),
                    ]),

                Section::make('Dış Bağlantılar')
                    ->description('Firma detay sayfasında yorumların üstünde gösterilecek bağlantılar')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('external_links')
                            ->label('Bağlantılar')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Bağlantı Metni')
                                    ->required()
                                    ->placeholder('örn: Google Haritalar'),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->required()
                                    ->url()
                                    ->placeholder('https://'),
                                Textarea::make('description')
                                    ->label('Açıklama (opsiyonel)')
                                    ->placeholder('Firmanın Google Maps konumu...')
                                    ->rows(2),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Bağlantı Ekle')
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? 'Yeni Bağlantı')
                            ->grid(1),
                    ]),

                Section::make('Premium & Durum')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_premium')
                                    ->label('Premium Firma')
                                    ->helperText('Premium firmalar öne çıkarılır'),
                                Toggle::make('is_verified')
                                    ->label('Doğrulanmış Firma')
                                    ->helperText('İletişim ve firma bilgileri kontrol edildiyse işaretleyin'),
                                DateTimePicker::make('premium_until')
                                    ->label('Premium Bitiş Tarihi')
                                    ->helperText('Boş bırakılırsa süresiz premium'),
                            ]),
                        Select::make('status')
                            ->label('Durum')
                            ->required()
                            ->options([
                                'pending' => 'Onay Bekliyor',
                                'active' => 'Aktif',
                                'passive' => 'Pasif',
                            ])
                            ->default('pending'),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title'),
                        Textarea::make('meta_description')
                            ->label('Meta Description'),
                    ]),
            ]);
    }
}
