<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kampanya Bilgileri')
                    ->schema([
                        Select::make('company_id')
                            ->label('Firma')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Kampanya Adı')
                            ->required()
                            ->helperText('Örn: Lezzet Durağı - 100 Rehber Yayını'),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('total_directories')
                                    ->label('Toplam Rehber')
                                    ->numeric()
                                    ->default(100)
                                    ->required(),
                                TextInput::make('daily_limit')
                                    ->label('Günlük Yayın')
                                    ->numeric()
                                    ->default(5)
                                    ->helperText('Günde kaç rehberde yayınlansın')
                                    ->required(),
                                DateTimePicker::make('start_date')
                                    ->label('Başlangıç')
                                    ->default(now()->addDay()),
                            ]),
                        Select::make('status')
                            ->label('Durum')
                            ->options([
                                'draft' => 'Taslak',
                                'active' => 'Aktif',
                                'completed' => 'Tamamlandı',
                                'cancelled' => 'İptal',
                            ])
                            ->default('draft')
                            ->required(),
                    ]),
            ]);
    }
}
