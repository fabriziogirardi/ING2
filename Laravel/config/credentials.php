<?php

return [

    'tomtom' => [
        'api_key' => env('TOMTOM_API_KEY'),
    ],
    'google_maps' => [
        'private_api_key'  => env('GOOGLE_MAPS_PRIVATE_API_KEY'),
        'public_api_key'   => env('GOOGLE_MAPS_PUBLIC_API_KEY'),
        'secret_signature' => env('GOOGLE_MAPS_SECRET_SIGNATURE'),
        'strict_search'    => env('GOOGLE_MAPS_STRICT_SEARCH'),
    ],

];
