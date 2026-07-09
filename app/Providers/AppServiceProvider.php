<?php

namespace App\Providers;

use App\Models\Directory;
use App\Models\SiteSetting;
use App\Observers\DirectoryObserver;
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

        View::composer('layouts.app', function ($view) {
            $view->with('settings', SiteSetting::getSettings());
            $view->with('directory', app()->bound('currentDirectory') ? app('currentDirectory') : null);
        });
    }
}
