<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use App\Models\CategoryDirectorySetting;
use App\Models\Directory;
use App\Support\ModelCache;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('icon')
                    ->label('İkon')
                    ->searchable(),
                TextColumn::make('companies_count')
                    ->label('Firma')
                    ->counts('companies')
                    ->sortable(),
                TextColumn::make('meta_title')
                    ->label('Meta Başlık')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_description')
                    ->label('Meta Açıklama')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                // Üstten bir rehber seçiliyse durum o rehbere göredir: genel olarak aktif olsa da bu rehberde gizlenmiş kategori "Bu rehberde gizli" görünür.
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->state(fn (Category $record): string => $record->status !== 'active' ? 'passive' : (self::hiddenHere($record) ? 'hidden' : 'active'))
                    ->formatStateUsing(fn ($state) => match ($state) { 'active' => 'Aktif', 'hidden' => 'Bu rehberde gizli', default => 'Pasif' })
                    ->color(fn ($state) => match ($state) { 'active' => 'success', 'hidden' => 'warning', default => 'gray' }),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn (Builder $query) => ($directory = self::directory())
                ? $query->with(['directorySettings' => fn ($settings) => $settings->where('directory_id', $directory->id)])
                : $query)
            ->defaultSort('name')
            ->filters([
                TernaryFilter::make('visible_here')
                    ->label('Seçili rehberde')
                    ->placeholder('Tümü')
                    ->trueLabel('Görünenler')
                    ->falseLabel('Gizlenenler')
                    ->visible(fn (): bool => self::directory() !== null)
                    ->queries(
                        true: fn (Builder $query) => $query->where('status', 'active')->visibleForDirectory(self::directory()),
                        false: fn (Builder $query) => $query->whereHas('directorySettings', fn ($s) => $s->where('directory_id', self::directory()?->id)->where('is_visible', false)),
                    ),
            ])
            ->recordActions([
                Action::make('toggle_here')
                    ->label(fn (Category $record): string => self::hiddenHere($record) ? 'Bu rehberde göster' : 'Bu rehberde gizle')
                    ->icon(fn (Category $record): string => self::hiddenHere($record) ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color('gray')
                    ->visible(fn (): bool => self::directory() !== null)
                    ->action(function (Category $record): void {
                        CategoryDirectorySetting::updateOrCreate(
                            ['category_id' => $record->id, 'directory_id' => self::directory()->id],
                            ['is_visible' => self::hiddenHere($record)],
                        );
                        ModelCache::bump('dir.'.self::directory()->id);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }

    private static function directory(): ?Directory
    {
        return app()->bound('currentDirectory') ? app('currentDirectory') : null;
    }

    private static function hiddenHere(Category $record): bool
    {
        $directory = self::directory();
        if (! $directory) {
            return false;
        }
        $setting = $record->relationLoaded('directorySettings')
            ? $record->directorySettings->firstWhere('directory_id', $directory->id)
            : $record->directorySettings()->where('directory_id', $directory->id)->first();

        return $setting !== null && ! $setting->is_visible;
    }
}
