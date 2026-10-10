<?php

namespace App\Filament\Resources\OwnerAccounts;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\OwnerAccounts\Pages\ListOwnerAccounts;
use App\Models\CompanyOwner;
use App\Models\Directory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/** Hesap açıp firma kaydını tamamlayan firma sahipleri: kişi, e-posta, telefon, firma ve kaynak bilgisiyle. */
class OwnerAccountResource extends Resource
{
    protected static ?string $model = CompanyOwner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Kayıtlı Firma Sahipleri';
    protected static ?string $modelLabel = 'Firma sahibi';
    protected static ?string $pluralModelLabel = 'Kayıtlı firma sahipleri';
    protected static string|\UnitEnum|null $navigationGroup = 'Firma Yönetimi';
    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        // Tüm rehberlerdeki kayıtlar görünsün; seçili rehber filtresi tablodaki süzgeçten yapılır.
        return CompanyOwner::query()
            ->withoutGlobalScopes()
            ->with([
                'user',
                'directory',
                'company' => fn ($query) => $query->withoutGlobalScopes()->with([
                    'city' => fn ($city) => $city->withoutGlobalScopes(),
                    'category' => fn ($category) => $category->withoutGlobalScopes(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::pendingQuery(static::getEloquentQuery())->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Onay bekleyen firma sayısı';
    }

    public static function pendingQuery(Builder $query): Builder
    {
        return $query->whereHas('company', fn ($company) => $company->withoutGlobalScopes()->where('status', 'pending'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Kayıt')->dateTime('d.m.Y H:i')->timezone('Europe/Istanbul')->sortable(),
                TextColumn::make('directory.name')->label('Rehber')->sortable(),
                TextColumn::make('company.name')->label('Firma')
                    ->description(fn (CompanyOwner $record) => collect([$record->company?->category?->name, $record->company?->city?->name])->filter()->implode(' · '))
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas('company', fn ($company) => $company->withoutGlobalScopes()->where('name', 'like', "%{$search}%"))),
                TextColumn::make('company.status')->label('Firma durumu')->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'active' => 'Yayında', 'pending' => 'Onay bekliyor', 'inactive' => 'Pasif', null => '-', default => $state,
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'active' => 'success', 'pending' => 'warning', default => 'gray',
                    }),
                TextColumn::make('user.name')->label('Yetkili')
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"))),
                TextColumn::make('user.email')->label('E-posta')->copyable()
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas('user', fn ($user) => $user->where('email', 'like', "%{$search}%"))),
                TextColumn::make('company.phone')->label('Telefon')->copyable()
                    ->searchable(query: fn (Builder $query, string $search) => $query->whereHas('company', fn ($company) => $company->withoutGlobalScopes()->where('phone', 'like', "%{$search}%"))),
                TextColumn::make('company.whatsapp')->label('WhatsApp')->placeholder('-')->toggleable(),
                TextColumn::make('source')->label('Kaynak')
                    ->state(fn (CompanyOwner $record) => $record->user?->utm_source
                        ? $record->user->utm_source.($record->user->utm_campaign ? ' / '.$record->user->utm_campaign : '')
                        : ($record->user?->referrer_host ?: 'Doğrudan'))
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('directory_id')->label('Rehber')
                    ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->all()),
                Filter::make('pending')->label('Yalnızca onay bekleyenler')
                    ->query(fn (Builder $query) => static::pendingQuery($query)),
                Filter::make('last_7_days')->label('Son 7 gün')
                    ->query(fn (Builder $query) => $query->where('company_owners.created_at', '>=', now()->subDays(7))),
            ])
            ->recordActions([
                Action::make('details')
                    ->label('Detay')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (CompanyOwner $record) => $record->company?->name ?? 'Firma sahibi')
                    ->modalContent(fn (CompanyOwner $record) => view('filament.partials.owner-account-details', ['owner' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Kapat'),
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (CompanyOwner $record): ?string {
                        $digits = \App\Models\ClaimInvite::mobileDigits($record->company?->whatsapp ?: $record->company?->phone);

                        return $digits ? 'https://wa.me/90'.$digits : null;
                    }, shouldOpenInNewTab: true)
                    ->visible(fn (CompanyOwner $record) => \App\Models\ClaimInvite::mobileDigits($record->company?->whatsapp ?: $record->company?->phone) !== null),
                Action::make('open_company')
                    ->label('Firmayı aç')
                    ->icon('heroicon-o-building-office')
                    ->url(fn (CompanyOwner $record) => $record->company ? CompanyResource::getUrl('edit', ['record' => $record->company->id]) : null)
                    ->visible(fn (CompanyOwner $record) => $record->company !== null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOwnerAccounts::route('/'),
        ];
    }
}
