<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Company;
use App\Models\Directory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kampanya Bilgileri')
                    ->schema([
                        Select::make('directory_id')
                            ->label('Kaynak Rehber')
                            ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->all())
                            ->default(fn () => app()->bound('currentDirectory') ? app('currentDirectory')->id : null)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('company_id', null)),
                        Select::make('company_id')
                            ->label('Firma')
                            ->options(fn (Get $get) => Company::withoutGlobalScope('directory')
                                ->where('directory_id', $get('directory_id'))
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->disabled(fn (Get $get): bool => blank($get('directory_id')))
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
