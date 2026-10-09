<?php

namespace App\Filament\Resources\AdCampaigns\Tables;

use App\Models\AdCampaign;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdCampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Reklam')->searchable()->sortable()
                    ->description(fn (AdCampaign $record) => $record->headline),
                TextColumn::make('type')->label('Tür')->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'paid' ? 'Ücretli' : 'Kendi reklamımız')
                    ->color(fn (string $state) => $state === 'paid' ? 'success' : 'gray'),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn (AdCampaign $record) => $record->isLive() ? 'Yayında' : ($record->status === 'paused' ? 'Durduruldu' : 'Takvim dışı'))
                    ->color(fn (AdCampaign $record) => $record->isLive() ? 'success' : 'warning'),
                TextColumn::make('placements')->label('Konum')
                    ->formatStateUsing(fn ($state) => collect((array) $state)->map(fn ($p) => AdCampaign::PLACEMENTS[$p] ?? $p)->implode(', '))
                    ->wrap(),
                TextColumn::make('targeting')->label('Hedef')
                    ->state(fn (AdCampaign $record) => collect([
                        filled($record->city_ids) ? count($record->city_ids).' şehir' : null,
                        filled($record->category_ids) ? count($record->category_ids).' kategori' : null,
                        filled($record->directory_ids) ? count($record->directory_ids).' rehber' : null,
                    ])->filter()->implode(' · ') ?: 'Hepsi'),
                TextColumn::make('impressions_total')->label('Gösterim')->numeric()->sortable(),
                TextColumn::make('clicks_total')->label('Tıklama')->numeric()->sortable(),
                TextColumn::make('ctr')->label('TO %')
                    ->state(fn (AdCampaign $record) => $record->ctr()),
                TextColumn::make('ends_at')->label('Bitiş')->dateTime('d.m.Y')->placeholder('Süresiz')->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('type')->label('Tür')->options(['paid' => 'Ücretli', 'house' => 'Kendi reklamımız']),
                SelectFilter::make('status')->label('Durum')->options(['active' => 'Yayında', 'paused' => 'Durduruldu']),
            ])
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
