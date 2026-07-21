<?php

namespace App\Filament\Resources\MembershipPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembershipPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable()
                    ->width('60px'),
                TextColumn::make('name')
                    ->label('Plan Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),
                TextColumn::make('billing_period')
                    ->label('Dönem')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'monthly' => 'Aylık',
                        'yearly' => 'Yıllık',
                        'onetime' => 'Tek Seferlik',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'monthly' => 'info',
                        'yearly' => 'success',
                        'onetime' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('features')
                    ->label('Özellik Sayısı')
                    ->formatStateUsing(fn($state): int => is_array($state) ? count($state) : 0),
                TextColumn::make('created_at')
                    ->label('Oluşturma')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
