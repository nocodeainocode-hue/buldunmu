<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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

                Section::make('Premium & Durum')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_premium')
                                    ->label('Premium Firma')
                                    ->helperText('Premium firmalar öne çıkarılır'),
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
