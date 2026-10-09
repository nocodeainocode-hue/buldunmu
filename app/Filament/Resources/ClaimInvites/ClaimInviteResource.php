<?php

namespace App\Filament\Resources\ClaimInvites;

use App\Filament\Resources\ClaimInvites\Pages\ListClaimInvites;
use App\Models\ClaimInvite;
use App\Models\Directory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClaimInviteResource extends Resource
{
    protected static ?string $model = ClaimInvite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Sahiplenme Davetleri';
    protected static ?string $modelLabel = 'Davet';
    protected static ?string $pluralModelLabel = 'Sahiplenme davetleri';
    protected static string|\UnitEnum|null $navigationGroup = 'Gelir';
    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')->label('Firma')->searchable()
                    ->description(fn (ClaimInvite $record) => $record->phone),
                TextColumn::make('directory.name')->label('Rehber')->sortable(),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn (string $state) => ClaimInvite::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'claimed' => 'success', 'clicked' => 'warning', 'sent' => 'info', 'opted_out' => 'danger', default => 'gray',
                    }),
                TextColumn::make('clicks')->label('Tıklama')->numeric()->sortable(),
                TextColumn::make('sent_at')->label('Gönderildi')->dateTime('d.m.Y H:i')->placeholder('-')->sortable(),
                TextColumn::make('last_clicked_at')->label('Son tıklama')->dateTime('d.m.Y H:i')->placeholder('-')->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('directory_id')->label('Rehber')
                    ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->all()),
                SelectFilter::make('status')->label('Durum')->options(ClaimInvite::STATUSES),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (ClaimInvite $record) => route('filament.admin.claim-invites.send', $record->id), shouldOpenInNewTab: true)
                    ->visible(fn (ClaimInvite $record) => $record->status !== 'opted_out' && $record->whatsappUrl() !== null),
                Action::make('opt_out')
                    ->label('Bir daha yazma')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Bu numaraya bir daha davet oluşturulmaz.')
                    ->visible(fn (ClaimInvite $record) => $record->status !== 'opted_out')
                    ->action(fn (ClaimInvite $record) => $record->forceFill(['status' => 'opted_out', 'opted_out_at' => now()])->save()),
                Action::make('copy_message')
                    ->label('Mesaj')
                    ->icon('heroicon-o-clipboard')
                    ->modalHeading('Hazır mesaj')
                    ->modalContent(fn (ClaimInvite $record) => view('filament.partials.claim-invite-message', ['invite' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Kapat'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClaimInvites::route('/'),
        ];
    }
}
