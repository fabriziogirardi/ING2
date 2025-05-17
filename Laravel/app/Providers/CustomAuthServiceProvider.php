<?php

namespace App\Providers;

use App\Auth\EloquentSplitTableUserProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CustomAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('split_table', function ($app, array $config) {
            return new EloquentSplitTableUserProvider($app['hash'], $config['model']);
        });
        
        Authenticate::redirectUsing(function (Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            
            if ($request->routeIs('a.*')) {
                return route('a.login');
            }
            
            return route('login');
        });
    }
}
