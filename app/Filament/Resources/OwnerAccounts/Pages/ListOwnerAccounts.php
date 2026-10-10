<?php

namespace App\Filament\Resources\OwnerAccounts\Pages;

use App\Filament\Resources\OwnerAccounts\OwnerAccountResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOwnerAccounts extends ListRecords
{
    protected static string $resource = OwnerAccountResource::class;

    public function getTabs(): array
    {
        $base = OwnerAccountResource::getEloquentQuery();

        return [
            'all' => Tab::make('Tümü')->badge((clone $base)->count()),
            'pending' => Tab::make('Onay bekleyen')
                ->badge(OwnerAccountResource::pendingQuery(clone $base)->count())
                ->modifyQueryUsing(fn (Builder $query) => OwnerAccountResource::pendingQuery($query)),
            'today' => Tab::make('Bugün')
                ->badge((clone $base)->where('company_owners.created_at', '>=', now()->startOfDay())->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('company_owners.created_at', '>=', now()->startOfDay())),
        ];
    }
}
