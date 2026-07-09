<?php

namespace App\Filament\Widgets;

use App\Models\Directory;
use App\Models\Company;
use App\Models\Campaign;
use App\Models\CampaignItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $todayItems = CampaignItem::whereDate('scheduled_for', today())->count();

        return [
            Stat::make('Rehber Siteleri', Directory::count())
                ->description('Aktif: ' . Directory::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),

            Stat::make('Toplam Firma', Company::count())
                ->description('Aktif: ' . Company::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Aktif Kampanyalar', Campaign::where('status', 'active')->count())
                ->description('Tamamlanan: ' . Campaign::where('status', 'completed')->count())
                ->descriptionIcon('heroicon-m-rocket-launch')
                ->color('warning'),

            Stat::make('Bugün Yayınlanacak', $todayItems)
                ->description('Planlanan toplam: ' . CampaignItem::where('status', 'scheduled')->count())
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
