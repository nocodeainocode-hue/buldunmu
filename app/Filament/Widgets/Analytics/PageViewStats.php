<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Pages\AnalyticsDashboard;
use App\Models\PageView;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PageViewStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected ?string $heading = 'Sayfa Görüntüleme İstatistikleri';

    /**
     * Only show on the AnalyticsDashboard page, not on the default dashboard.
     */
    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof AnalyticsDashboard;
    }

    protected function getStats(): array
    {
        $directoryId = $this->getPageDirectoryId();

        $totalQuery = PageView::withoutGlobalScope('directory');
        $todayQuery = PageView::withoutGlobalScope('directory')->whereDate('created_at', today());

        if ($directoryId) {
            $totalQuery->directory($directoryId);
            $todayQuery->directory($directoryId);
        }

        $totalViews = $totalQuery->count();
        $todayViews = $todayQuery->count();

        $yesterdayViews = PageView::withoutGlobalScope('directory')
            ->when($directoryId, fn ($q) => $q->directory($directoryId))
            ->whereDate('created_at', today()->subDay())
            ->count();

        $trendIcon = 'heroicon-m-arrow-trending-up';
        $trendColor = 'success';
        if ($yesterdayViews > 0 && $todayViews < $yesterdayViews) {
            $trendIcon = 'heroicon-m-arrow-trending-down';
            $trendColor = 'danger';
        }

        return [
            Stat::make('Toplam Görüntülenme', number_format($totalViews))
                ->description('Veritabanındaki tüm sayfa görüntülemeleri')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),

            Stat::make('Bugünkü Trafik', number_format($todayViews))
                ->description('Dün: '.number_format($yesterdayViews))
                ->descriptionIcon($trendIcon)
                ->color($trendColor),
        ];
    }

    private function getPageDirectoryId(): ?int
    {
        $directoryId = $this->pageFilters['directoryFilter'] ?? null;

        return filled($directoryId) ? (int) $directoryId : null;
    }
}
