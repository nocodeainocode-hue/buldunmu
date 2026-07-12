<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Post;
use App\Models\Directory;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Görsel')->circular()->disk('public'),
                TextColumn::make('title')->label('Başlık')->searchable()->sortable(),
                TextColumn::make('directories.name')->label('Rehberler')->badge()->separator(','),
                TextColumn::make('content_type')->label('Tür')->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'comparison' => 'Karşılaştırma',
                        'alternatives' => 'Alternatifler',
                        'local' => 'Yerel',
                        'answers' => 'Soru - Cevap',
                        'data' => 'Veri',
                        default => 'Rehber',
                    }),
                TextColumn::make('primary_query')->label('Ana Sorgu')->searchable()->limit(32),
                TextColumn::make('is_indexable')->label('Index')->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Index' : 'Noindex')
                    ->color(fn($state) => $state ? 'success' : 'warning'),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(function ($state, Post $record) {
                        return match ($record->fresh()->status ?? $state) {
                            'published' => 'Yayında',
                            default => 'Taslak',
                        };
                    })
                    ->color(fn($s) => $s === 'published' ? 'success' : 'gray'),
                TextColumn::make('published_at')->label('Tarih')->dateTime('d.m.Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('directory_id')
                    ->label('Rehber')
                    ->options(fn() => Directory::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        $directoryId = $data['value'] ?? null;

                        return $query->when(
                            filled($directoryId),
                            fn(Builder $query) => $query->whereHas(
                                'directories',
                                fn(Builder $directoryQuery) => $directoryQuery->whereKey($directoryId)
                            )
                        );
                    }),
                SelectFilter::make('content_type')->label('İçerik Türü')->options([
                    'guide'=>'Rehber','comparison'=>'Karşılaştırma','alternatives'=>'Alternatifler',
                    'local'=>'Yerel','answers'=>'Soru - Cevap','data'=>'Veri',
                ]),
                SelectFilter::make('status')->label('Durum')->options(['draft'=>'Taslak','published'=>'Yayında']),
            ])
            ->actions([
                Action::make('publish')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->requiresConfirmation(false)
                    ->visible(fn(Post $r) => $r->status !== 'published')
                    ->action(function (Post $r, $livewire) {
                        $r->update(['status' => 'published', 'published_at' => $r->published_at ?? now()]);
                        $r->refresh();
                        $livewire->refresh();
                    })
                    ->after(fn($livewire) => $livewire->dispatch('$refresh')),
                Action::make('unpublish')
                    ->icon('heroicon-o-eye-slash')
                    ->color('gray')
                    ->requiresConfirmation(false)
                    ->visible(fn(Post $r) => $r->status === 'published')
                    ->action(function (Post $r, $livewire) {
                        $r->update(['status' => 'draft']);
                        $r->refresh();
                        $livewire->refresh();
                    })
                    ->after(fn($livewire) => $livewire->dispatch('$refresh')),
            ]);
    }
}
