<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Services\CampaignPlanService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('directory.name')
                    ->label('Kaynak Rehber')
                    ->badge(),
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Taslak',
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
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
                    ->modalDescription('Kaynak rehber hariç aktif rehberler için yayın kayıtları oluşturulacak. Aynı kampanya ikinci kez üretilemez.')
                    ->action(function ($record) {
                        $result = app(CampaignPlanService::class)->generate($record);

                        if ($result['already_generated']) {
                            Notification::make()
                                ->title('Kampanya zaten planlanmış')
                                ->body('Bu kampanya için yeni mükerrer yayın kaydı oluşturulmadı.')
                                ->warning()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('Yayınlar oluşturuldu!')
                            ->success()
                            ->body("{$result['created']} yayın {$result['total']} hedef rehbere planlandı. Kaynak rehber hariç tutuldu; her gün {$result['daily_limit']} yayın yapılacak.")
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'draft'),

                Action::make('export_csv')
                    ->label('CSV Rapor')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record) => route('filament.admin.campaigns.export-csv', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->items()->count() > 0),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
