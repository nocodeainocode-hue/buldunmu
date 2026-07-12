<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Yazisi')
                    ->schema([
                        Select::make('directories')
                            ->label('Birincil Rehber')
                            ->relationship(
                                'directories',
                                'name',
                                modifyQueryUsing: fn(Builder $query) => $query->select([
                                    'directories.id',
                                    'directories.name',
                                ])
                            )
                            ->multiple()
                            ->maxItems(1)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->minItems(1)
                            ->rules(['required', 'array', 'min:1'])
                            ->validationMessages(['required'=>'En az bir rehber secmelisiniz','min'=>'En az bir rehber secmelisiniz'])
                            ->helperText('Her yazı yalnızca bir rehberde yayınlanır. Aynı içeriği başka rehbere bağlamayın.'),
                        Grid::make(2)->schema([
                            TextInput::make('title')->label('Baslik')->required()->live(onBlur: true)
                                ->afterStateUpdated(fn($s, callable $set) => $set('slug', Str::slug($s))),
                            TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('content_type')
                                ->label('İçerik Türü')
                                ->options([
                                    'guide' => 'Seçim / Hizmet Rehberi',
                                    'comparison' => 'Karşılaştırma',
                                    'alternatives' => 'Alternatifler',
                                    'local' => 'Yerel İçerik',
                                    'answers' => 'Soru - Cevap',
                                    'data' => 'Veri / Araştırma',
                                ])
                                ->default('guide')
                                ->required(),
                            Select::make('search_intent')
                                ->label('Arama Niyeti')
                                ->options([
                                    'informational' => 'Bilgi Edinme',
                                    'commercial' => 'Karşılaştırma / Ticari Araştırma',
                                    'local' => 'Yerel Hizmet Arama',
                                    'transactional' => 'Firma ile İletişim',
                                ])
                                ->required(),
                        ]),
                        TextInput::make('primary_query')
                            ->label('Sahiplenilen Ana Sorgu')
                            ->placeholder('su arıtma cihazı seçmeden önce')
                            ->helperText('Ağ genelinde benzersizdir; başka bir rehber aynı ana sorguyu sahiplenemez.')
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Str::lower(trim($state)) : null),
                        Grid::make(2)->schema([
                            TextInput::make('target_city_slug')->label('Hedef Şehir Slug')->placeholder('tekirdag'),
                            TextInput::make('target_category_slug')->label('Hedef Kategori Slug')->placeholder('su-aritma'),
                        ]),
                        Textarea::make('excerpt')->label('Ozet')->rows(2)->columnSpanFull(),
                        RichEditor::make('content')->label('Icerik')->required()->columnSpanFull(),
                        FileUpload::make('image')->label('Gorsel')->image()->disk('public')->directory('posts'),
                    ]),
                Section::make('Editoryal Güven')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('author_name')->label('Yazar'),
                            TextInput::make('reviewer_name')->label('Kontrol Eden'),
                        ]),
                        TagsInput::make('sources')
                            ->label('Kaynak URL’leri')
                            ->placeholder('https://...')
                            ->helperText('Her kaynak adresini Enter ile ekleyin.'),
                        Grid::make(2)->schema([
                            TagsInput::make('pros')->label('Artılar'),
                            TagsInput::make('cons')->label('Eksiler'),
                        ]),
                        Repeater::make('faq_items')
                            ->label('Sık Sorulan Sorular')
                            ->schema([
                                TextInput::make('question')->label('Soru')->required(),
                                Textarea::make('answer')->label('Cevap')->rows(3)->required(),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull(),
                        Textarea::make('editorial_notes')
                            ->label('Editör Notu')
                            ->rows(3)
                            ->helperText('Yazıda kullanılacak özgün veri, görüşme, fotoğraf veya kontrol notları.'),
                    ]),
                Section::make('Yayin')->schema([
                    Grid::make(2)->schema([
                        Select::make('status')->label('Durum')->options(['draft'=>'Taslak','published'=>'Yayinda'])->default('draft'),
                        DateTimePicker::make('published_at')->label('Yayin Tarihi')->default(now()),
                    ]),
                    Toggle::make('is_indexable')
                        ->label('Google’da indekslenebilir')
                        ->default(true),
                    TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->helperText('Boşsa yazının kendi URL’si kullanılır. Başka yerdeki asıl içeriğin kopyasıysa asıl URL’yi yazın.'),
                ]),
            ]);
    }
}
