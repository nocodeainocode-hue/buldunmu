<?php

namespace App\Filament\Resources\CompanyReviews\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompanyReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')->label('Firma')->searchable()->sortable(),
                TextColumn::make('name')->label('Yazan')->searchable(),
                TextColumn::make('rating')->label('Puan')->badge()->sortable(),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn($s) => match ($s) {
                        'approved' => 'Yayında',
                        'rejected' => 'Reddedildi',
                        default => 'Onay Bekliyor',
                    })
                    ->color(fn($s) => match ($s) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Durum')->options([
                    'pending' => 'Onay Bekliyor',
                    'approved' => 'Yayında',
                    'rejected' => 'Reddedildi',
                ]),
            ]);
    }
}
