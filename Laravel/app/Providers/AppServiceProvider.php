<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Flatten an array while keeping it's keys, even non-incremental numeric ones, in tact.
         *
         * Unless $dotNotification is set to true, if nested keys are the same as any
         * parent ones, the nested ones will supersede them.
         *
         * @param  int  $depth  How many levels deep to flatten the array
         * @param  bool  $dotNotation  Maintain all parent keys in dot notation
         */
        Collection::macro('flattenKeepingKeys', function ($depth = 1, $dotNotation = false, $separator = '_') {
            if ($depth) {
                $newArray = [];
                foreach ($this->items as $parentKey => $value) {
                    if (is_array($value)) {
                        $valueKeys = array_keys($value);
                        foreach ($valueKeys as $key) {
                            $subValue = $value[$key];
                            $newKey   = $key;
                            if ($dotNotation) {
                                $newKey = "{$parentKey}{$separator}{$key}";
                                if ($dotNotation !== true) {
                                    $newKey = "{$dotNotation}{$separator}{$newKey}";
                                }

                                if (is_array($value[$key])) {
                                    $subValue = collect($value[$key])->flattenKeepingKeys($depth - 1, $newKey, $separator)->toArray();
                                }
                            }
                            $newArray[$newKey] = $subValue;
                        }
                    } else {
                        $newArray[$parentKey] = $value;
                    }
                }

                $this->items = collect($newArray)->flattenKeepingKeys(--$depth, $dotNotation, $separator)->toArray();
            }

            return collect($this->items);
        });
        FilamentAsset::register([
            Css::make('custom-stylesheet', 'http://localhost:5173/resources/css/app.css'),
        ]);
    }
}
