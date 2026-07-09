<?php

namespace App\Filament\Resources\CompanyReviews\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\CompanyReview;

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
            ])
            ->actions([
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation(false)
                    ->visible(fn(CompanyReview $r) => $r->status !== 'approved')
                    ->action(function (CompanyReview $r) {
                        $r->update(['status' => 'approved', 'approved_at' => now()]);
                        return redirect(request()->header('Referer'));
                    }),
                Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation(false)
                    ->visible(fn(CompanyReview $r) => $r->status !== 'rejected')
                    ->action(function (CompanyReview $r) {
                        $r->update(['status' => 'rejected']);
                        return redirect(request()->header('Referer'));
                    }),
            ]);
    }
}
