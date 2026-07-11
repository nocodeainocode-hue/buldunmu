<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\CompanyReviews\CompanyReviewResource;
use App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource;
use App\Filament\Resources\ListingRequests\ListingRequestResource;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = -2;

    protected function getViewData(): array
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        return [
            'directory' => $directory,
            'actions' => [
                ['label'=>'Firma Ekle','description'=>'Tek firma kaydı oluştur','icon'=>'heroicon-o-plus','url'=>CompanyResource::getUrl('create'),'color'=>'primary'],
                ['label'=>'Excel ile Yükle','description'=>'Çoklu rehbere firma aktar','icon'=>'heroicon-o-arrow-up-tray','url'=>DiscoveredCompanyResource::getUrl('import'),'color'=>'success'],
                ['label'=>'Yorumları Yönet','description'=>'Bekleyen yorumları incele','icon'=>'heroicon-o-star','url'=>CompanyReviewResource::getUrl(),'color'=>'warning'],
                ['label'=>'Firma Talepleri','description'=>'Kullanıcı başvurularını aç','icon'=>'heroicon-o-inbox-arrow-down','url'=>ListingRequestResource::getUrl(),'color'=>'info'],
            ],
        ];
    }
}
