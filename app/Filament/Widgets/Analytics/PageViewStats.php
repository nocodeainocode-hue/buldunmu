<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PageViewStats extends BaseWidget
{
    protected static ?int $sort = 0;

    protected ?string $heading = 'Sayfa Görüntüleme İstatistikleri';

    /**
     * Only show on the AnalyticsDashboard page, not on the default dashboard.
     */
    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof \App\Filament\Pages\AnalyticsDashboard;
    }

    protected function getStats(): array
    {
        $directoryId = $this->getPageDirectoryId();

        $totalQuery = PageView::query();
        $todayQuery = PageView::query()->whereDate('created_at', today());

        if ($directoryId) {
            $totalQuery->directory($directoryId);
            $todayQuery->directory($directoryId);
        }

        $totalViews = $totalQuery->count();
        $todayViews = $todayQuery->count();

        $yesterdayViews = PageView::query()
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
                ->description('Dün: ' . number_format($yesterdayViews))
                ->descriptionIcon($trendIcon)
                ->color($trendColor),
        ];
    }

    private function getPageDirectoryId(): ?int
    {
        $page = $this->getPage();

        if ($page && property_exists($page, 'directoryFilter')) {
            return $page->directoryFilter;
        }

        return null;
    }
}
