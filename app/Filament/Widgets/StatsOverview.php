<?php

namespace App\Filament\Widgets;

use App\Models\Directory;
use App\Models\Company;
use App\Models\CompanyReview;
use App\Models\ContactMessage;
use App\Models\ListingRequest;
use App\Models\Post;
use App\Filament\Resources\Directories\DirectoryResource;
use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\CompanyReviews\CompanyReviewResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\ListingRequests\ListingRequestResource;
use App\Filament\Resources\Posts\PostResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -1;
    protected ?string $heading = 'Genel Durum';
    protected ?string $description = 'Seçili rehberin güncel içerik ve işlem özeti';

    protected function getStats(): array
    {
        $pendingReviews = CompanyReview::where('status', 'pending')->count();
        $newRequests = ListingRequest::where('status', 'new')->count();
        $newMessages = ContactMessage::where('status', 'new')->count();
        $newToday = Company::whereDate('created_at', today())->count();
        $publishedPosts = Post::where('status', 'published')->count();

        return [
            Stat::make('Rehber Siteleri', Directory::count())
                ->description('Aktif: ' . Directory::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary')
                ->url(DirectoryResource::getUrl()),

            Stat::make('Toplam Firma', Company::count())
                ->description('Bugün eklenen: ' . $newToday . ' | Aktif: ' . Company::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success')
                ->url(CompanyResource::getUrl()),

            Stat::make('Yorumlar', CompanyReview::count())
                ->description('Onay bekleyen: ' . $pendingReviews)
                ->descriptionIcon('heroicon-m-chat-bubble-left')
                ->color($pendingReviews > 0 ? 'warning' : 'gray')
                ->url(CompanyReviewResource::getUrl()),

            Stat::make('Firma Talepleri', ListingRequest::count())
                ->description('Yeni talep: ' . $newRequests)
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($newRequests > 0 ? 'warning' : 'gray')
                ->url(ListingRequestResource::getUrl()),

            Stat::make('İletişim Mesajları', ContactMessage::count())
                ->description('Okunmamış: ' . $newMessages)
                ->descriptionIcon('heroicon-m-envelope')
                ->color($newMessages > 0 ? 'danger' : 'gray')
                ->url(ContactMessageResource::getUrl()),

            Stat::make('Blog Yazıları', $publishedPosts)
                ->description('Toplam: ' . Post::count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->url(PostResource::getUrl()),
        ];
    }
}
