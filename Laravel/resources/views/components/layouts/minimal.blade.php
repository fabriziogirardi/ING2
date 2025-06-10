<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col">
        <x-navigation.navbar.minimal />
        <div class="container mx-auto px-4 py-8 grid grid-cols-1 min-h-full content-center lg:my-auto">
            {{ $slot }}
        </div>
     </div>
</body>
</html>
