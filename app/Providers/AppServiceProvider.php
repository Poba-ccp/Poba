<?php
// FILE: app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL; // <-- 1. Add this line here

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Add this block to force secure HTTPS links on Render
        if (config('app.env') === 'production' || app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Use our custom pagination view
        Paginator::defaultView('vendor.pagination.simple-default');
        Paginator::defaultSimpleView('vendor.pagination.simple-default');
    }
}
