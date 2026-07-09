<?php

namespace App\Filament\Resources\CompanyReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Yorum')
                ->schema([
                    Select::make('company_id')
                        ->label('Firma')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Grid::make(2)->schema([
                        TextInput::make('name')->label('Ad Soyad')->required()->maxLength(120),
                        TextInput::make('email')->label('E-posta')->email()->maxLength(160),
                    ]),
                    Grid::make(2)->schema([
                        Select::make('rating')
                            ->label('Puan')
                            ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                            ->required(),
                        Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Onay Bekliyor',
                                'approved' => 'Yayında',
                                'rejected' => 'Reddedildi',
                            ])
                            ->required(),
                    ]),
                    Textarea::make('comment')->label('Yorum')->rows(5)->required()->columnSpanFull(),
                ]),
        ]);
    }
}
