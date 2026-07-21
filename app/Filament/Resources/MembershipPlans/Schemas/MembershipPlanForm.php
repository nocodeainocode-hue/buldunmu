<?php

namespace App\Filament\Resources\MembershipPlans\Schemas;

use App\Models\Directory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MembershipPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Plan Adı')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL için benzersiz tanımlayıcı'),
                        Select::make('directory_id')
                            ->label('Rehber')
                            ->options(fn() => Directory::orderBy('name')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required()
                            ->default(fn() => app()->bound('currentDirectory') ? app('currentDirectory')->id : null),
                        TextInput::make('price')
                            ->label('Fiyat')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix('₺'),
                        Select::make('currency')
                            ->label('Para Birimi')
                            ->options([
                                'TRY' => '₺ TRY',
                                'USD' => '$ USD',
                                'EUR' => '€ EUR',
                            ])
                            ->default('TRY')
                            ->required(),
                        Select::make('billing_period')
                            ->label('Fatura Dönemi')
                            ->options([
                                'monthly' => 'Aylık',
                                'yearly' => 'Yıllık',
                                'onetime' => 'Tek Seferlik',
                            ])
                            ->default('yearly')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                Section::make('Özellikler')
                    ->schema([
                        Repeater::make('features')
                            ->label('Plan Özellikleri')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlık')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('description')
                                    ->label('Açıklama')
                                    ->maxLength(500),
                                TextInput::make('icon')
                                    ->label('İkon (opsiyonel)')
                                    ->helperText('Heroicon adı veya SVG. Örn: heroicon-o-check-circle')
                                    ->maxLength(255),
                            ])
                            ->columns(3)
                            ->addActionLabel('Özellik Ekle')
                            ->defaultItems(3)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                    ]),
            ]);
    }
}
