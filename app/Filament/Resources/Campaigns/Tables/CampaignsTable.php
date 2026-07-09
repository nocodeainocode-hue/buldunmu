<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Models\CampaignItem;
use App\Models\Directory;
use App\Services\AnchorTextService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kampanya')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Firma')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_directories')
                    ->label('Rehber')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('daily_limit')
                    ->label('Günlük')
                    ->numeric(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Taslak',
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('items_count')
                    ->label('Yayın')
                    ->counts('items'),
                TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'draft' => 'Taslak',
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                    ]),
            ])
            ->recordActions([
                Action::make('generate_items')
                    ->label('100 Rehbere Yayın Oluştur')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kampanya Yayınları Oluştur')
                    ->modalDescription('Tüm aktif rehberler için yayın kayıtları oluşturulacak ve 20 güne yayılacak. Onaylıyor musunuz?')
                    ->action(function ($record) {
                        $directories = Directory::where('status', 'active')
                            ->limit($record->total_directories)
                            ->get();

                        $company = $record->company;
                        $totalDirs = $directories->count();
                        $perDay = $record->daily_limit;
                        $day = 0;
                        $count = 0;

                        foreach ($directories as $i => $dir) {
                            if ($i > 0 && $i % $perDay === 0) $day++;

                            $anchor = AnchorTextService::generate($company, $dir);
                            $slug = Str::slug($company->name . '-' . ($dir->plate_code ?? $dir->slug ?? $i));

                            CampaignItem::create([
                                'campaign_id' => $record->id,
                                'directory_id' => $dir->id,
                                'company_id' => $company->id,
                                'slug' => $slug,
                                'description' => $company->short_description ?? $company->name . ' - ' . $dir->name,
                                'anchor_text' => $anchor['anchor_text'],
                                'link_type' => $anchor['link_type'],
                                'scheduled_for' => now()->addDays($day),
                                'status' => 'scheduled',
                            ]);
                            $count++;
                        }

                        $record->update(['status' => 'active', 'start_date' => now()]);

                        Notification::make()
                            ->title('Yayınlar oluşturuldu!')
                            ->success()
                            ->body("{$count} yayın {$totalDirs} rehbere planlandı. Her gün {$perDay} yayın yapılacak.")
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'draft'),

                Action::make('export_csv')
                    ->label('CSV Rapor')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn($record) => route('filament.admin.campaigns.export-csv', $record))
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->items()->count() > 0),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
