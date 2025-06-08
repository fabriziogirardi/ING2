<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>
            {{ $title ?? config('app.name') }}
        </title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        @livewireStyles
        @filamentStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" />
        <style>
            .gradient {
                background: linear-gradient(90deg, #045893 0%, #429ede 100%);
            }
        </style>
    </head>
    <body class="flex flex-col leading-normal tracking-normal text-gray-700 gradient min-h-screen" style="font-family: 'Source Sans Pro', sans-serif;">
        <!--Nav-->
        <x-navigation.navbar.complete />

        <div class="pt-20">
            {{ $slot }}
        </div>

        <x-footer />

        @filamentScripts
        @livewireScripts
    </body>
</html>
