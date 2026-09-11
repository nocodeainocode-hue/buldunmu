<?php

namespace App\Filament\Pages;

use App\Models\Directory;
use App\Services\CompanySlugService;
use App\Services\DirectoryManagementService;
use App\Support\BlogLayout;
use App\Support\TurkeyCities;
use App\View\Helpers\ThemeHelper;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\Rule;

class DirectoryManagementCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'Rehber Yönetim Merkezi';

    protected static ?string $title = 'Rehber Yönetim Merkezi';

    protected static string|\UnitEnum|null $navigationGroup = 'Site Yönetimi';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.directory-management-center';

    public ?array $data = [];

    public ?int $directoryId = null;

    public function mount(DirectoryManagementService $service): void
    {
        $directory = $this->selectedDirectory();

        if (! $directory) {
            return;
        }

        $this->directoryId = $directory->id;
        $this->form->fill($service->formData($directory));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Rehber Ayarları')
                    ->persistTabInQueryString('sekme')
                    ->tabs([
                        Tab::make('Genel')
                            ->icon(Heroicon::OutlinedBuildingOffice2)
                            ->schema($this->generalTab()),
                        Tab::make('Tasarım')
                            ->icon(Heroicon::OutlinedSwatch)
                            ->schema($this->designTab()),
                        Tab::make('Ana Sayfa')
                            ->icon(Heroicon::OutlinedHome)
                            ->schema($this->homepageTab()),
                        Tab::make('SEO')
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema($this->seoTab()),
                        Tab::make('İçerik')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema($this->contentTab()),
                        Tab::make('Özellikler')
                            ->icon(Heroicon::OutlinedSquaresPlus)
                            ->schema($this->featuresTab()),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(DirectoryManagementService $service): void
    {
        $directory = $this->selectedDirectory();

        if (! $directory || $directory->id !== $this->directoryId) {
            Notification::make()
                ->title('Rehber seçimi değişti')
                ->body('Yanlış rehberin etkilenmemesi için kayıt durduruldu. Sayfayı yenileyip tekrar deneyin.')
                ->danger()
                ->send();

            return;
        }

        $service->update($directory, $this->form->getState());
        $this->form->fill($service->formData($directory->fresh()));

        Notification::make()
            ->title('Rehber ayarları kaydedildi')
            ->body($directory->name.' güncellendi. Diğer rehberler etkilenmedi.')
            ->success()
            ->send();
    }

    public function getHeading(): string
    {
        return $this->selectedDirectory()?->name ?? static::$title;
    }

    public function getSubheading(): ?string
    {
        $directory = $this->selectedDirectory();

        return $directory
            ? $directory->domain.' için tüm görünüm, içerik ve yayın ayarları'
            : 'Düzenlemek istediğiniz rehberi üst menüden seçin.';
    }

    protected function getHeaderActions(): array
    {
        $directory = $this->selectedDirectory();

        if (! $directory?->domain) {
            return [];
        }

        return [
            Action::make('siteyiAc')
                ->label('Siteyi Aç')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url('https://'.$directory->domain)
                ->openUrlInNewTab(),
        ];
    }

    private function selectedDirectory(): ?Directory
    {
        return app()->bound('currentDirectory') ? app('currentDirectory') : null;
    }

    private function generalTab(): array
    {
        return [
            Section::make('Kimlik ve Domain')
                ->description('Bu alanlar seçili rehberin adı ve erişim adresini belirler.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')->label('Rehber Adı')->required()->maxLength(255),
                        TextInput::make('slug')
                            ->label('Rehber Slug')
                            ->required()
                            ->rules([Rule::unique('directories', 'slug')->ignore($this->directoryId)]),
                    ]),
                    TextInput::make('domain')
                        ->label('Domain')
                        ->prefix('https://')
                        ->required()
                        ->dehydrateStateUsing(fn ($state) => Directory::normalizeDomain($state))
                        ->rules([
                            fn () => function (string $attribute, mixed $value, \Closure $fail): void {
                                $domain = Directory::normalizeDomain($value);
                                $exists = Directory::query()
                                    ->where('domain', $domain)
                                    ->when($this->directoryId, fn ($query) => $query->where('id', '!=', $this->directoryId))
                                    ->exists();

                                if ($exists) {
                                    $fail('Bu domain başka bir rehberde kullanılıyor.');
                                }
                            },
                        ])
                        ->helperText('Protokol ve www yazmanız gerekmez.'),
                    Grid::make(2)->schema([
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
                ]),
            Section::make('Yayın Durumu')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('plan')->label('Plan')->options([
                            'free' => 'Ücretsiz',
                            'basic' => 'Temel',
                            'pro' => 'Profesyonel',
                        ])->required(),
                        Select::make('status')->label('Durum')->options([
                            'active' => 'Aktif',
                            'passive' => 'Pasif',
                        ])->required(),
                        DateTimePicker::make('expires_at')->label('Bitiş Tarihi'),
                    ]),
                ]),
        ];
    }

    private function designTab(): array
    {
        return [
            Section::make('Görsel Stil')
                ->description('Tema seçimi tüm yerleşimi değiştirir. Altındaki renkler yalnızca seçili temanın varsayılanlarını ezer.')
                ->schema([
                    Select::make('template')
                        ->label('Tema')
                        ->options(ThemeHelper::templateSelectOptions())
                        ->searchable()
                        ->required(),
                    Grid::make(4)->schema([
                        ColorPicker::make('theme_primary')->label('Ana Renk'),
                        ColorPicker::make('theme_secondary')->label('İkincil Renk'),
                        ColorPicker::make('theme_accent')->label('Vurgu Rengi'),
                        ColorPicker::make('theme_bg')->label('Sayfa Zemini'),
                    ]),
                    Grid::make(4)->schema([
                        ColorPicker::make('theme_bg_card')->label('Kart Zemini'),
                        ColorPicker::make('theme_text')->label('Yazı Rengi'),
                        ColorPicker::make('theme_text_muted')->label('Soluk Yazı'),
                        ColorPicker::make('theme_border')->label('Kenarlık'),
                    ]),
                    Grid::make(2)->schema([
                        ColorPicker::make('theme_hero_gradient_from')->label('Hero Başlangıç'),
                        ColorPicker::make('theme_hero_gradient_to')->label('Hero Bitiş'),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('theme_font_body')->label('Font')->options([
                            'Inter, sans-serif' => 'Inter',
                            'Georgia, serif' => 'Georgia',
                            'system-ui, sans-serif' => 'System UI',
                            'Poppins, sans-serif' => 'Poppins',
                            'Roboto, sans-serif' => 'Roboto',
                        ]),
                        Select::make('theme_border_radius')->label('Köşe Yuvarlaklığı')->options([
                            '0' => 'Keskin',
                            '0.25rem' => 'Hafif',
                            '0.5rem' => 'Orta',
                            '0.75rem' => 'Yuvarlak',
                            '1rem' => 'Çok Yuvarlak',
                            '1.5rem' => 'Tam Yuvarlak',
                        ]),
                        Select::make('theme_card_shadow')->label('Kart Gölgesi')->options([
                            'none' => 'Yok',
                            '0 1px 3px rgba(0,0,0,0.08)' => 'Hafif',
                            '0 4px 12px rgba(0,0,0,0.1)' => 'Orta',
                            '0 8px 24px rgba(0,0,0,0.15)' => 'Belirgin',
                        ]),
                    ]),
                ]),
        ];
    }

    private function homepageTab(): array
    {
        return [
            Section::make('Ana Sayfa Metinleri')
                ->schema([
                    TextInput::make('homepage_title')->label('Ana Sayfa Başlığı')->maxLength(255),
                    Textarea::make('homepage_subtitle')->label('Ana Sayfa Alt Başlığı')->rows(3)->maxLength(255),
                ]),
            Section::make('İletişim Bilgileri')
                ->description('Header, footer ve iletişim sayfasında kullanılan rehber iletişim bilgileri.')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('phone')->label('Telefon')->tel(),
                        TextInput::make('whatsapp')->label('WhatsApp')->tel(),
                        TextInput::make('email')->label('E-posta')->email(),
                    ]),
                    Textarea::make('address')->label('Adres')->rows(3)->columnSpanFull(),
                ]),
        ];
    }

    private function seoTab(): array
    {
        return [
            Section::make('Arama Görünümü')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Başlık')
                        ->maxLength(70)
                        ->helperText('Boş bırakılırsa rehber adı ve sayfa içeriğinden otomatik oluşturulur.'),
                    Textarea::make('meta_description')
                        ->label('Meta Açıklama')
                        ->rows(3)
                        ->maxLength(180),
                ]),
            Section::make('Firma URL Yapısı')
                ->schema([
                    Select::make('slug_pattern')
                        ->label('Slug Deseni')
                        ->options(CompanySlugService::selectOptions())
                        ->searchable()
                        ->required()
                        ->helperText('Yalnızca yeni eklenen firmalara uygulanır. Mevcut firma adresleri değişmez.'),
                ]),
        ];
    }

    private function contentTab(): array
    {
        return [
            Section::make('Coğrafi Kapsam')
                ->schema([
                    Select::make('geography_mode')
                        ->label('Kapsam')
                        ->options([
                            'national' => 'Türkiye Geneli (81 İl)',
                            'local' => 'Tek Şehir Odaklı',
                            'custom' => 'Seçili Şehirler',
                        ])
                        ->required()
                        ->live(),
                    Select::make('primary_city_slug')
                        ->label('Ana Şehir')
                        ->options(TurkeyCities::options())
                        ->searchable()
                        ->visible(fn ($get) => $get('geography_mode') === 'local')
                        ->required(fn ($get) => $get('geography_mode') === 'local'),
                    Select::make('featured_city_slugs')
                        ->label('Gösterilecek Şehirler')
                        ->options(TurkeyCities::options())
                        ->multiple()
                        ->searchable()
                        ->visible(fn ($get) => $get('geography_mode') === 'custom')
                        ->required(fn ($get) => $get('geography_mode') === 'custom'),
                    Toggle::make('group_other_cities')
                        ->label('Kalan şehirleri "Diğer İller" altında göster')
                        ->visible(fn ($get) => in_array($get('geography_mode'), ['local', 'custom'], true)),
                ]),
            Section::make('Blog Kimliği')
                ->schema([
                    Select::make('blog_layout')
                        ->label('Blog Görünümü')
                        ->options(BlogLayout::OPTIONS)
                        ->required(),
                    Textarea::make('editorial_voice')
                        ->label('Yayın Dili ve Editoryal Kimlik')
                        ->rows(4)
                        ->helperText('Bu rehbere özel içerik tonu, hedef kitle ve yerel yaklaşımı yazın.'),
                ]),
            Section::make('Sabit Sayfalar')
                ->description('Boş bırakılan sayfalarda sistemin varsayılan metni kullanılır.')
                ->collapsible()
                ->schema([
                    RichEditor::make('page_contents.about')->label('Hakkımızda'),
                    RichEditor::make('page_contents.contact')->label('İletişim'),
                    RichEditor::make('page_contents.privacy')->label('Gizlilik Politikası'),
                    RichEditor::make('page_contents.terms')->label('Kullanım Şartları'),
                ]),
        ];
    }

    private function featuresTab(): array
    {
        return [
            Section::make('Üyelik ve Gelir Özellikleri')
                ->schema([
                    Toggle::make('show_membership_plans')
                        ->label('Üyelik paketlerini ön yüzde göster')
                        ->helperText('Bu rehbere özel paket yoksa genel paketler gösterilir.'),
                ]),
        ];
    }
}
