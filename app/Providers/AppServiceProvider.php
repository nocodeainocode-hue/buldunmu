<?php

namespace App\Providers;

use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\SiteSetting;
use App\Observers\DirectoryObserver;
use App\Observers\ListingRequestObserver;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Directory::observe(DirectoryObserver::class);
        ListingRequest::observe(ListingRequestObserver::class);

        // Veritabanı ve uygulama UTC kalır; yönetim panelindeki tarih alanları Türkiye saatiyle girilir/gösterilir.
        FilamentTimezone::set('Europe/Istanbul');

        View::composer('layouts.app', function ($view) {
            $view->with('settings', SiteSetting::getSettings());
            $view->with('directory', app()->bound('currentDirectory') ? app('currentDirectory') : null);
        });
    }
}
