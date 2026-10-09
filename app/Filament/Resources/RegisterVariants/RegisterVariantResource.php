<?php

namespace App\Filament\Resources\RegisterVariants;

use App\Filament\Resources\RegisterVariants\Pages\CreateRegisterVariant;
use App\Filament\Resources\RegisterVariants\Pages\EditRegisterVariant;
use App\Filament\Resources\RegisterVariants\Pages\ListRegisterVariants;
use App\Models\RegisterVariant;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class RegisterVariantResource extends Resource
{
    protected static ?string $model = RegisterVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Kayıt Sayfası Varyantları';
    protected static ?string $modelLabel = 'Kayıt sayfası varyantı';
    protected static ?string $pluralModelLabel = 'Kayıt sayfası varyantları';
    protected static string|\UnitEnum|null $navigationGroup = 'Gelir';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Varyant')
                ->description('Reklam adresinin sonuna ?v=anahtar ekleyin. Örn. https://rehberiniz.com/firma-kayit?v=ucretsiz&utm_source=google&utm_campaign=yaz. Şehirli reklamlarda &sehir=tekirdag&kategori=su-aritma de ekleyebilirsiniz.')
                ->schema([
                    TextInput::make('key')->label('Anahtar (adreste ?v=…)')->required()->maxLength(40)
                        ->alphaDash()->unique(ignoreRecord: true),
                    TextInput::make('name')->label('Dahili ad')->required()->maxLength(255),
                    Select::make('status')->label('Durum')->required()->default('active')
                        ->options(['active' => 'Yayında', 'paused' => 'Durduruldu']),
                ])->columns(3),
            Section::make('Sayfa metni')
                ->description('Kullanılabilir yer tutucular: {rehber}, {sehir}, {kategori}. Doldurulamayan bir yer tutucu varsa (örn. adreste sehir yoksa) varsayılan metin gösterilir.')
                ->schema([
                    TextInput::make('badge')->label('Üst rozet')->maxLength(80)->placeholder('Ücretsiz firma kaydı'),
                    TextInput::make('headline')->label('Başlık')->required()->maxLength(140)->columnSpanFull(),
                    Textarea::make('subheadline')->label('Alt başlık')->rows(2)->maxLength(300)->columnSpanFull(),
                    TextInput::make('button_text')->label('Son adım düğmesi')->maxLength(60)->placeholder('Ücretsiz Profilimi Oluştur'),
                    Repeater::make('benefits')->label('Fayda maddeleri (boşsa varsayılanlar)')->maxItems(4)
                        ->schema([
                            TextInput::make('title')->label('Başlık')->required()->maxLength(80),
                            TextInput::make('text')->label('Açıklama')->maxLength(160),
                        ])->columns(2)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Ad')->searchable(),
            TextColumn::make('key')->label('Anahtar')->badge()->copyable(),
            TextColumn::make('headline')->label('Başlık')->limit(60),
            TextColumn::make('status')->label('Durum')->badge()
                ->formatStateUsing(fn (string $state) => $state === 'active' ? 'Yayında' : 'Durduruldu')
                ->color(fn (string $state) => $state === 'active' ? 'success' : 'gray'),
        ])->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegisterVariants::route('/'),
            'create' => CreateRegisterVariant::route('/create'),
            'edit' => EditRegisterVariant::route('/{record}/edit'),
        ];
    }
}
