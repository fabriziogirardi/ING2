<div>
    <div class="bg-gray-100 h-160 px-32 py-20">
        <h1 class="w-full my-2 text-5xl font-bold leading-tight text-center text-gray-800">
            Nuestras sucursales
        </h1>
        <div class="w-full mb-12">
            <div class="h-1 mx-auto gradient w-64 opacity-25 my-0 py-0 rounded-t"></div>
        </div>
        <div id="map" class="z-40 h-160 border border-gray-300 rounded-lg shadow"></div>
    </div>
    <div class="bg-gray-100 pb-64"></div>

    <script>
        async function initMap() {
            const {InfoWindow, Map} = google.maps;
            const {AdvancedMarkerElement} = google.maps.marker;

            var branches = @json($branches);

            const map = new Map(document.getElementById('map'), {
                zoom: 16, // será sobrescrito por fitBounds()
                center: {lat: -34.920749, lng: -57.953604},
                disableDefaultUI: true,
                mapId: "{{ $map_id }}",
            });

            if (!branches || branches.length === 0) return;

            const bounds = new google.maps.LatLngBounds();
            const infowindow = new InfoWindow();

            branches.forEach(function (branch) {
                const longitude = parseFloat(branch.longitude);
                const latitude = parseFloat(branch.latitude);

                const pinElement = document.createElement('div');
                pinElement.innerHTML = `
            <img src="{{ asset('BranchesMapPin.png') }}" class="w-16 h-16" alt="Pin">
        `;

                const marker = new AdvancedMarkerElement({
                    position: {lat: latitude, lng: longitude},
                    map: map,
                    title: branch.name,
                    content: pinElement
                });

                // Agregar el punto a los límites
                bounds.extend({lat: latitude, lng: longitude});

                marker.addListener('gmp-click', function () {
                    const content = `
                <div class="text-center">
                    <h3 class="text-lg font-semibold">${branch.name}</h3>
                    <p class="text-sm">${branch.description}</p>
                    <p class="text-sm">${branch.address}</p>
                </div>
            `;
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                });
            });

            // Ajustar vista para mostrar todos los marcadores
            map.fitBounds(bounds);

            google.maps.event.addListenerOnce(map, 'bounds_changed', function () {
                if (map.getZoom() > 16) map.setZoom(16);
            });
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{$api_key}}&callback=initMap&v=weekly&libraries=maps,marker&loading=async"></script>
</div>
