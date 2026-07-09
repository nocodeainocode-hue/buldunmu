<?php

namespace App\Filament\Widgets;

use App\Models\Directory;
use App\Models\Company;
use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\CompanyReview;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $todayItems = CampaignItem::whereDate('scheduled_for', today())->count();
        $pendingReviews = CompanyReview::where('status', 'pending')->count();
        $newToday = Company::whereDate('created_at', today())->count();
        $publishedPosts = Post::where('status', 'published')->count();

        return [
            Stat::make('Rehber Siteleri', Directory::count())
                ->description('Aktif: ' . Directory::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),

            Stat::make('Toplam Firma', Company::count())
                ->description('Bugün eklenen: ' . $newToday . ' | Aktif: ' . Company::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Yorumlar', CompanyReview::count())
                ->description('Onay bekleyen: ' . $pendingReviews)
                ->descriptionIcon('heroicon-m-chat-bubble-left')
                ->color($pendingReviews > 0 ? 'warning' : 'gray'),

            Stat::make('Blog Yazıları', $publishedPosts)
                ->description('Toplam: ' . Post::count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Aktif Kampanyalar', Campaign::where('status', 'active')->count())
                ->description('Bugün yayınlanacak: ' . $todayItems)
                ->descriptionIcon('heroicon-m-rocket-launch')
                ->color('danger'),
        ];
    }
}
