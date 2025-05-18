<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mapa de sucursales</title>
</head>
    <body>
        <div id="map" style="height: 750px; width: 100%;">
        </div>

        <script>
            function initMap() {
                var sucursales = @json($sucursales);

                if(sucursales.length === 0) {
                    return;
                }

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 16,
                    center: {lat: -34.920749, lng: -57.953604},
                    // scrollwheel: false,
                    // draggable: false,
                    styles: [
                        { featureType: "poi", stylers: [{ visibility: "off" }] },
                        { featureType: "transit", stylers: [{ visibility: "off" }] },
                    ],
                    disableDefaultUI: true,
                });

                var infowindow = new google.maps.InfoWindow();

                sucursales.forEach(function(sucursal) {

                    var longitud = parseFloat(sucursal.longitude);
                    var latitud = parseFloat(sucursal.latitude);


                    var marker = new google.maps.Marker({
                        position: {lat: latitud, lng: longitud},
                        map: map,
                        title: sucursal.name,
                        icon: {
                            url: '{{ asset('Pin.png') }}',
                            scaledSize: new google.maps.Size(40, 40)
                        }
                    });

                    google.maps.event.addListener(marker, 'click', function() {
                        var content = '<div style="text-align: center;">' +
                            '<h3>' + sucursal.name + '</h3>' +
                            '<p>Dirección: ' + sucursal.address + '</p>' +
                            '<p>Teléfono: ' + "+54 9 221 123-456" + '</p>' +
                            '</div>';
                        infowindow.setContent(content);
                        infowindow.open(map, marker);
                    });
                });
            }
        </script>


        <script async defer src ="https://maps.googleapis.com/maps/api/js?key={{$api_key}}&callback=initMap&libraries=marker"></script>
    </body>
</html>
