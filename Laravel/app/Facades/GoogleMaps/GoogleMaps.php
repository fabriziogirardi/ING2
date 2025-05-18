<?php

namespace App\Facades\GoogleMaps;

use App\Facades\Maps\Maps;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleMaps extends Maps
{
    /**
     * GoogleMaps Geocode API Endpoint, currently unused but who knows.
     * 2023 still not working for ARG
     */
    private const string GOOGLE_GEOCODE = 'https://maps.googleapis.com/maps/api/geocode/json?key={key}&address={address}&components=country:ar';

    /**
     * GoogleMaps Autocomplete API Endpoint
     * Recieves a string and return possible addresses
     */
    private const string GOOGLE_AUTOCOMPLETE = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?key={key}&components=country:ar&location=-34.9213986,-57.95448159999998&radius=15000&region=ar&language=es&input={address}';

    /**
     * GoogleMaps FindPlaces API EndpointMaps, currently unused.
     * Not related right now, but probably used in a future.
     */
    private const GOOGLE_FIND_PLACE = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key={key}&inputtype=textquery&input={address}&fields=business_status,formatted_address,geometry,icon,icon_mask_base_uri,icon_background_color,name,photo,place_id,plus_code,type';

    /**
     * GoogleMaps Place Details API Endpoint
     * Retrieves details from a place, such as coordinates, viewport, etc
     */
    private const GOOGLE_PLACE_DETAILS = 'https://maps.googleapis.com/maps/api/place/details/json?key={key}&place_id={place_id}';

    /**
     * GoogleMaps Static Map API Endpoint
     * Generates a URL with a static map to be shown as an image
     */
    private const GOOGLE_STATIC_MAP = 'https://maps.googleapis.com/maps/api/staticmap?key={key}&center={center}&markers={center}&size=400x400&zoom=18';

    /**
     * GoogleMaps Distance Matrix API Endpoint
     * Calculates driving distance between two points
     */
    private const GOOGLE_DISTANCE_MATRIX = 'https://maps.googleapis.com/maps/api/distancematrix/json?key={key}&origins=place_id:{origin}&destinations=place_id:{destination}';

    /**
     * @var Repository|Application|mixed
     */
    private string $private_api_key;

    /**
     * @var Repository|Application|mixed
     */
    private bool $strict_search;

    /**
     * @var Repository|Application|mixed
     */
    private mixed $public_api_key;

    /**
     * @var Repository|Application|mixed
     */
    private mixed $secret_signature;

    /**
     * Constructor for the class
     *
     * It automatically retrieves default configuration
     * values from config folder and set them as properties
     * inside the object scope.
     */
    public function __construct()
    {
        $this->private_api_key  = config('credentials.google_maps.private_api_key');
        $this->public_api_key   = config('credentials.google_maps.public_api_key');
        $this->secret_signature = config('credentials.google_maps.secret_signature');
        $this->strict_search    = config('credentials.google_maps.strict_search');
    }

    /**
     * Method to parse the URL and replace static predefined strings
     * with the actual variables to be used.
     */
    private function parseUrl(array $parse_variables, string $url): string
    {
        $search  = [];
        $replace = [];

        foreach ($parse_variables as $key => $value) {
            $search[]  = '{'.$key.'}';
            $replace[] = $value;
        }

        return Str::replace($search, $replace, $url);
    }

    /**
     * Make a request to the GoogleMaps Autocomplete Endpoint
     */
    public function searchGoogleMapsAutocomplete(string $input): array
    {
        $full_url = $this->parseUrl(['key' => $this->private_api_key, 'address' => $input], self::GOOGLE_AUTOCOMPLETE.$this->generateStrictSearchUrl());

        $response = Http::get($full_url);

        return collect($response->json())->toArray();
    }

    /**
     * Make a request to the GoogleMaps Place Details Endpoint
     */
    public function searchGoogleMapsPlaceDetails(string $input): Collection
    {
        $full_url = str_replace(['{key}', '{place_id}'], [$this->private_api_key, $input], self::GOOGLE_PLACE_DETAILS);

        $response = Http::get($full_url);

        //		dd(self::getAddressCoordinates(collect($response->json())));
        //		dd(collect(self::getLocationCoordinates(collect($response->json())))->flattenKeepingKeys(2, true, "_")->toArray());

        return collect($response->json());
    }

    /**
     * Get coordinates from a PlaceDetails response.
     * Coordinates will be in an array format following
     *
     *
     * @return array ['place_id','formatted_address','name',
     *               'map_preview_url','lat','lng','viewport_northeast_lat',
     *               'viewport_northeast_lng','viewport_southwest_lat',
     *               'viewport_southwest_lng']
     */
    public function getAddressCoordinates(Collection $address): array
    {
        $place_id          = data_get($address, 'result.place_id');
        $formatted_address = data_get($address, 'result.formatted_address');
        $name              = data_get($address, 'result.name');
        $coordinates       = data_get($address, 'result.geometry.location');
        $map_preview_url   = $this->getMapThumbnail($coordinates['lat'], $coordinates['lng']);
        $viewport          = $this->getLocationViewportCoordinates($address);

        return array_merge(
            [
                'place_id'          => $place_id,
                'formatted_address' => $formatted_address,
                'name'              => $name,
                'map_preview_url'   => $map_preview_url,
            ],
            $coordinates,
            $viewport);
    }

    /**
     * Get type flags from the address response
     *
     *
     * @return array Array ot types.
     */
    public function getAddressTypes(Collection $address): array
    {
        return data_get($address, 'result.types');
    }

    /**
     * Get address components from the address response
     *
     *
     * @return array Array of components
     */
    public function getAddressComponents(Collection $address): array
    {
        return data_get($address, 'result.address_components');
    }

    /**
     * Get address viewport bound coordinates from the address response
     */
    public function getLocationViewportCoordinates(Collection $address): array
    {
        return collect(data_get($address, 'result.geometry'))
            ->except(['location'])
            ->flattenKeepingKeys(2, true, '_')
            ->toArray();
    }

    /**
     * Generates the url with strict restriction to geocode or not, based
     * on configuration set in the config file.
     * Without strict search, results can include public places.
     *
     * @return string
     */
    private function generateStrictSearchUrl()
    {
        return $this->strict_search ? '&types=geocode' : '';
    }

    /**
     * Retrieve the map thumbnail image for the given coordinates.
     */
    public function getMapThumbnail(float $lat, float $lng): string
    {
        $full_url = str_replace(['{key}', '{center}'], [$this->public_api_key, $lat.','.$lng], self::GOOGLE_STATIC_MAP);

        return $this->signUrl($full_url);
    }

    /* -------------------------------------------
     *
     * Start of Google Sign URL Predefined Methods
     *
     */
    private function encodeBase64UrlSafe($value)
    {
        return str_replace(['+', '/'], ['-', '_'],
            base64_encode($value));
    }

    // Decode a string from URL-safe base64
    private function decodeBase64UrlSafe($value)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'],
            $value));
    }

    // Sign a URL with a given crypto key.
    // Note that this URL must be properly URL-encoded
    private function signUrl($myUrlToSign, $privateKey = null)
    {
        $privateKey = $privateKey ?? $this->secret_signature;
        // parse the url
        $url = parse_url($myUrlToSign);

        $urlPartToSign = $url['path'].'?'.$url['query'];

        // Decode the private key into its binary format
        $decodedKey = $this->decodeBase64UrlSafe($privateKey);

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac('sha1', $urlPartToSign, $decodedKey, true);

        $encodedSignature = $this->encodeBase64UrlSafe($signature);

        return $myUrlToSign.'&signature='.$encodedSignature;
    }
}
