@extends('layouts.kurir_page')
@section('title', 'Live Tracking')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="absolute top-32 left-0 right-0 px-4" x-data="{ showModal: false, selectedData: {} }" @keydown.escape.window="showModal = false">
    {{-- Mengubah h-[20rem] menjadi min-h-[35rem] untuk memberikan ruang lebih pada peta --}}
    <div class="max-w-[90rem] min-h-[35rem] mx-auto bg-white rounded-xl shadow-xl p-6 flex flex-col gap-6 items-center">
        <h1 class="text-2xl font-bold mb-4">Aktifkan Live Tracking Kurir</h1>
        
        <div class="flex w-full gap-4 items-center mb-4">
            <input type="text" id="tracking_number" placeholder="Masukkan Nomor Resi"
                class="border border-gray-300 rounded px-4 py-2 flex-grow" />

            <button onclick="startTracking()" id="startBtn"
                class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded shadow flex-shrink-0">
                Mulai Tracking
            </button>
        </div>

        <p id="status" class="mt-4 text-sm text-gray-500"></p>

        {{-- Peta Anda membutuhkan tinggi 350px, jadi card harus cukup tinggi untuk menampungnya --}}
        <div id="map" class="mt-6 rounded-md w-full" style="height: 350px;"></div> {{-- Tambahkan w-full ke peta agar mengisi lebar yang tersedia --}}
    </div>
</div>

<script>
    let map, marker;

    function initMap(lat, long) {
        if (!map) {
            map = L.map('map').setView([lat, long], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        }

        if (marker) {
            marker.setLatLng([lat, long]);
        } else {
            marker = L.marker([lat, long]).addTo(map)
                .bindPopup('Lokasi Kurir Saat Ini').openPopup();
        }

        map.setView([lat, long], 15);
    }

    function startTracking() {
        const trackingNumber = document.getElementById('tracking_number').value;
        const statusText = document.getElementById('status');

        if (!trackingNumber) return alert('Nomor resi wajib diisi.');

        if (!navigator.geolocation) {
            return alert('Geolocation tidak didukung di browser ini.');
        }

        statusText.innerText = 'Tracking dimulai...';

        navigator.geolocation.watchPosition(function(position) {
            const lat = position.coords.latitude;
            const long = position.coords.longitude;

            initMap(lat, long);

            // Invalidate map size after it becomes visible or its container changes size
            // This is crucial for Leaflet maps inside hidden or dynamic containers
            if (map) {
                map.invalidateSize();
            }

            fetch("{{ route('kurir.update_location') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tracking_number: trackingNumber,
                    lat: lat,
                    long: long
                })
            })
            .then(res => res.json())
            .then(data => {
                statusText.innerText = 'Lokasi dikirim: ' + new Date().toLocaleTimeString();
            })
            .catch(err => {
                statusText.innerText = 'Gagal kirim lokasi: ' + err.message;
            });

        }, function(error) {
            statusText.innerText = 'Gagal dapatkan lokasi: ' + error.message;
        }, {
            enableHighAccuracy: true
        });
    }
</script>
@endsection