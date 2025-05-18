<div id="map" style="height: 100%; width: 100%;"></div>

<script>
    async function initMap() {
        const { InfoWindow, Map } = google.maps;
        const { AdvancedMarkerElement } = google.maps.marker;

        var branches = @json($branches);

        const map = new Map(document.getElementById('map'), {
            zoom: 16,
            center: {lat: -34.920749, lng: -57.953604},
            disableDefaultUI: true,
            mapId: "{{ config('credentials.google_maps.map_id') }}",
        });

        if (!branches || branches.length === 0) return;

        const infowindow = new InfoWindow();

        branches.forEach(function (branch) {
            const longitude = parseFloat(branch.longitude);
            const latitude = parseFloat(branch.latitude);

            const pinElement = document.createElement('div');
            pinElement.innerHTML = `
                <img src="{{ asset('BranchesMapPin.png') }}" style="width:40px;height:40px;" alt="Pin">
            `;

            const marker = new AdvancedMarkerElement({
                position: {lat: latitude, lng: longitude},
                map: map,
                title: branch.name,
                content: pinElement
            });

            marker.addListener('gmp-click', function () {
                const content = `
                    <div style="text-align: center;">
                        <h3>${branch.name}</h3>
                        <p>Dirección: ${branch.address}</p>
                        <p>Teléfono: +54 9 221 123-456</p>
                    </div>
                `;
                infowindow.setContent(content);
                infowindow.open(map, marker);
            });
        });
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{$api_key}}&callback=initMap&v=weekly&libraries=maps,marker&loading=async"></script>
