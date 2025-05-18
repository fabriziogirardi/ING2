<?php

namespace App\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Facades\GoogleMaps\GoogleMaps
 *
 * @mixin \App\Facades\GoogleMaps\GoogleMaps
 *
 * @method static array searchGoogleMapsAutocomplete(string $input) Search the string using the Autocomplete API endpoint of the GoogleMaps API Suite, and return the results.
 * @method static Collection searchGoogleMapsPlaceDetails(string $input) Search for a specific unique place in GoogleMaps API using PlaceID parameter.
 * @method static string getMapThumbnail(float $lat, float $lng) Returns the URL of a thumbnail for the specified coordinates.
 */
class GoogleMaps extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'GoogleMaps';
    }
}
