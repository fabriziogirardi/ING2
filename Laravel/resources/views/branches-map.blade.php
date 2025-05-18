<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mapa de Sucursales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="height: 700px">
    <x-branches-map-view :branches="$branches" />
</body>
</html>
