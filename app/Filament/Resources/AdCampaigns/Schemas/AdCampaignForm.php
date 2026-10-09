<?php

namespace App\Filament\Resources\AdCampaigns\Schemas;

use App\Models\AdCampaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Reklam')
                ->schema([
                    TextInput::make('name')->label('Dahili ad')->required()->maxLength(255)
                        ->helperText('Yalnızca yönetim panelinde ve raporlarda görünür.'),
                    Select::make('type')->label('Tür')->required()->default('house')
                        ->options(['paid' => 'Ücretli reklamveren', 'house' => 'Kendi reklamımız'])
                        ->helperText('Ücretli reklamveren, kendi reklamlarımızın her zaman önüne geçer.'),
                    Select::make('status')->label('Durum')->required()->default('active')
                        ->options(['active' => 'Yayında', 'paused' => 'Durduruldu']),
                    CheckboxList::make('placements')->label('Gösterileceği konumlar')->required()
                        ->options(AdCampaign::PLACEMENTS)->default(['top', 'bottom'])->columns(2),
                ])->columns(2),

            Section::make('İçerik')
                ->description('Görsel yüklenirse yalnızca görsel gösterilir. Yüklenmezse aşağıdaki metinlerle renkli banner oluşur.')
                ->schema([
                    TextInput::make('headline')->label('Başlık')->required()->maxLength(120),
                    TextInput::make('cta_label')->label('Düğme yazısı')->default('Detaylı bilgi')->maxLength(40),
                    Textarea::make('body')->label('Kısa metin')->rows(2)->maxLength(200)->columnSpanFull(),
                    TextInput::make('link_url')->label('Hedef adres')->url()->required()->maxLength(500)->columnSpanFull()
                        ->helperText('Tıklamalarda adrese utm_source (rehber), utm_medium=banner ve utm_campaign eklenir.'),
                    FileUpload::make('image_path')->label('Banner görseli (isteğe bağlı)')
                        ->image()->disk('public')->directory('ads')->maxSize(2048)->columnSpanFull()
                        ->helperText('Sayfa üstü için 970×90, sayfa altı için 970×250 piksel önerilir (JPG/PNG/WebP, en fazla 2 MB).'),
                    ColorPicker::make('bg_color')->label('Arka plan')->default('#14213d'),
                    ColorPicker::make('text_color')->label('Yazı rengi')->default('#ffffff'),
                    ColorPicker::make('accent_color')->label('Düğme rengi')->default('#ffc233'),
                ])->columns(3),

            Section::make('Hedefleme')
                ->description('Boş bırakılan alan "hepsi" demektir. Hedef ne kadar dar olursa reklam o kadar önceliklidir (ücretli reklamveren her zaman önde).')
                ->schema([
                    Select::make('directory_ids')->label('Rehberler')->multiple()->searchable()
                        ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->all())
                        ->placeholder('Tüm rehberler'),
                    Select::make('city_ids')->label('Şehirler')->multiple()->searchable()
                        ->options(fn () => City::withoutGlobalScope('directory')->whereNull('directory_id')->orderBy('name')->pluck('name', 'id')->all())
                        ->placeholder('Her yer')
                        ->helperText('Şehir sayfası, o şehirdeki firma sayfaları ve yalnızca o şehre odaklı rehberlerde gösterilir.'),
                    Select::make('category_ids')->label('Kategoriler')->multiple()->searchable()
                        ->options(fn () => Category::withoutGlobalScope('directory')->whereNull('directory_id')->orderBy('name')->pluck('name', 'id')->all())
                        ->placeholder('Her kategori'),
                ])->columns(3),

            Section::make('Takvim ve dağılım')
                ->schema([
                    DateTimePicker::make('starts_at')->label('Başlangıç')->helperText('Boşsa hemen başlar.'),
                    DateTimePicker::make('ends_at')->label('Bitiş')->helperText('Boşsa süresiz.'),
                    TextInput::make('weight')->label('Ağırlık')->numeric()->minValue(1)->default(1)
                        ->helperText('Aynı öncelikteki reklamlar arasında dönüş sıklığı (2 = iki kat sık).'),
                ])->columns(3),
        ]);
    }
}
