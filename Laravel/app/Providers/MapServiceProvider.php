<?php

namespace App\Providers;

use App\Facades\GoogleMaps\GoogleMaps;
use Illuminate\Support\ServiceProvider;

class MapServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function boot(): void
    {
        $this->app->bind('GoogleMaps', function () {
            return new GoogleMaps;
        });
    }
}
