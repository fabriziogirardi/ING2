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
        Auth::provider('split_table', static function ($app, array $config) {
            return new EloquentSplitTableUserProvider($app['hash'], $config['model']);
        });

        Auth::macro('getCurrentGuard', function () {
            $guards = array_keys(config('auth.guards'));

            return collect($guards)->first(static function ($guard) {
                return auth()->guard($guard)->check();
            });
        });

        Auth::macro('currentUser', function () {
            $guards = array_keys(config('auth.guards'));

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return Auth::guard($guard)->user();
                }
            }

            return null;
        });

        Authenticate::redirectUsing(static function (Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if ($request->routeIs('manager.*')) {
                return route('manager.login');
            }

            if ($request->routeIs('employee.*')) {
                return route('employee.login');
            }

            return route('customer.login');
        });
    }
}
