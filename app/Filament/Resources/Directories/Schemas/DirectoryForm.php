<?php

namespace App\Filament\Resources\Directories\Schemas;

use App\View\Helpers\ThemeHelper;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use App\Services\CompanySlugService;
use App\Support\TurkeyCities;

class DirectoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rehber Bilgileri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Rehber Adı')
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
                        TextInput::make('domain')
                            ->label('Domain')
                            ->helperText('Örn: firmarehberi.com.tr')
                            ->prefix('https://')
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('directories/logos')
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('200')
                                    ->imageResizeTargetHeight('60'),
                                FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('directories/favicons')
                                    ->imageResizeTargetWidth('32')
                                    ->imageResizeTargetHeight('32'),
                            ]),
                        Select::make('template')
                            ->label('Tema (Görsel Stil)')
                            ->options(ThemeHelper::templateSelectOptions())
                            ->default('default')
                            ->live()
                            ->helperText('Seçtiğin temaya göre site tasarımı tamamen değişir'),
                        Select::make('slug_pattern')
                            ->label('Slug Deseni')
                            ->options(CompanySlugService::selectOptions())
                            ->searchable()
                            ->helperText('Yalnızca yeni firmalarda uygulanır; mevcut firma URL’lerini değiştirmez.')
                            ->default('{name}-{city}'),
                        Select::make('geography_mode')
                            ->label('Coğrafi Kapsam')
                            ->options([
                                'national' => 'Türkiye Geneli (81 İl)',
                                'local' => 'Tek Şehir Odaklı',
                                'custom' => 'Seçili Şehirler',
                            ])
                            ->default('national')
                            ->required()
                            ->live(),
                        Select::make('primary_city_slug')
                            ->label('Ana Şehir')
                            ->options(TurkeyCities::options())
                            ->searchable()
                            ->visible(fn($get) => $get('geography_mode') === 'local')
                            ->required(fn($get) => $get('geography_mode') === 'local'),
                        Select::make('featured_city_slugs')
                            ->label('Gösterilecek Şehirler')
                            ->options(TurkeyCities::options())
                            ->multiple()
                            ->searchable()
                            ->visible(fn($get) => $get('geography_mode') === 'custom')
                            ->required(fn($get) => $get('geography_mode') === 'custom'),
                        Toggle::make('group_other_cities')
                            ->label('Kalan şehirleri "Diğer İller" altında göster')
                            ->default(true)
                            ->visible(fn($get) => in_array($get('geography_mode'), ['local', 'custom'], true)),
                    ]),

                Section::make('Tema Özelleştirme')
                    ->description('Aşağıdaki değerleri değiştirerek seçili temayı özelleştirebilirsin. Boş bırakırsan tema varsayılanı kullanılır.')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                ColorPicker::make('theme_primary')
                                    ->label('Ana Renk')
                                    ->hint('Butonlar, linkler'),
                                ColorPicker::make('theme_secondary')
                                    ->label('İkincil Renk'),
                                ColorPicker::make('theme_accent')
                                    ->label('Vurgu Rengi')
                                    ->hint('Rozetler, ikonlar'),
                                ColorPicker::make('theme_bg')
                                    ->label('Arkaplan')
                                    ->hint('Sayfa zemini'),
                            ]),
                        Grid::make(4)
                            ->schema([
                                ColorPicker::make('theme_bg_card')
                                    ->label('Kart Arkaplanı'),
                                ColorPicker::make('theme_text')
                                    ->label('Yazı Rengi'),
                                ColorPicker::make('theme_text_muted')
                                    ->label('Soluk Yazı'),
                                ColorPicker::make('theme_border')
                                    ->label('Border Rengi'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                ColorPicker::make('theme_hero_gradient_from')
                                    ->label('Hero Başlangıç'),
                                ColorPicker::make('theme_hero_gradient_to')
                                    ->label('Hero Bitiş'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('theme_font_body')
                                    ->label('Font')
                                    ->options([
                                        'Inter, sans-serif' => 'Inter (Modern)',
                                        'Georgia, serif' => 'Georgia (Serif)',
                                        'system-ui, sans-serif' => 'System UI',
                                        'Poppins, sans-serif' => 'Poppins',
                                        'Roboto, sans-serif' => 'Roboto',
                                    ]),
                                Select::make('theme_border_radius')
                                    ->label('Köşe Yuvarlaklığı')
                                    ->options([
                                        '0' => 'Keskin',
                                        '0.25rem' => 'Hafif',
                                        '0.5rem' => 'Orta',
                                        '0.75rem' => 'Yuvarlak',
                                        '1rem' => 'Çok Yuvarlak',
                                        '1.5rem' => 'Tam Yuvarlak',
                                    ]),
                                Select::make('theme_card_shadow')
                                    ->label('Kart Gölgesi')
                                    ->options([
                                        'none' => 'Yok',
                                        '0 1px 3px rgba(0,0,0,0.08)' => 'Hafif',
                                        '0 4px 12px rgba(0,0,0,0.1)' => 'Orta',
                                        '0 8px 24px rgba(0,0,0,0.15)' => 'Belirgin',
                                    ]),
                            ]),
                    ]),

                Section::make('Plan & Durum')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('plan')
                                    ->label('Plan')
                                    ->options([
                                        'free' => 'Ücretsiz',
                                        'basic' => 'Temel',
                                        'pro' => 'Profesyonel',
                                    ])
                                    ->default('free'),
                                Select::make('status')
                                    ->label('Durum')
                                    ->options([
                                        'active' => 'Aktif',
                                        'passive' => 'Pasif',
                                    ])
                                    ->default('active'),
                                DateTimePicker::make('expires_at')
                                    ->label('Bitiş Tarihi'),
                            ]),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')->label('Meta Title'),
                        Textarea::make('meta_description')->label('Meta Description'),
                    ]),

                Section::make('Özel Sayfa İçerikleri')
                    ->description('Bu rehbere özel hakkımızda, iletişim, gizlilik ve kullanım şartları sayfalarının içerikleri. Boş bırakılırsa varsayılan içerik gösterilir.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        RichEditor::make('page_contents.about')
                            ->label('Hakkımızda')
                            ->placeholder('Bu rehber hakkında açıklama...'),
                        RichEditor::make('page_contents.contact')
                            ->label('İletişim')
                            ->placeholder('İletişim bilgileri ve form açıklaması...'),
                        RichEditor::make('page_contents.privacy')
                            ->label('Gizlilik Politikası')
                            ->placeholder('Gizlilik politikası metni...'),
                        RichEditor::make('page_contents.terms')
                            ->label('Kullanım Şartları')
                            ->placeholder('Kullanım şartları metni...'),
                    ]),
            ]);
    }
}
