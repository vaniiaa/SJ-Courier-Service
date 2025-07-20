@extends('layouts.admin')

@section('title', 'Live Tracking')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .tracking-error {
        color: #ef4444;
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .leaflet-map {
        height: 300px;
        border-radius: 0.5rem;
    }
</style>

<div class="absolute top-32 left-0 right-0 px-4">
    <div class="max-w-[90rem] min-h-[35rem] mx-auto bg-white rounded-xl shadow-xl p-6 flex flex-col gap-6 items-center">
        <h1 class="text-2xl font-bold mb-4">Live Tracking Admin</h1>
        
        <div class="flex w-full gap-4 items-center mb-4">
            <input type="text" id="tracking_number" placeholder="Masukkan Nomor Resi"
                class="border border-gray-300 rounded px-4 py-2 flex-grow bg-white" />
            <button onclick="trackShipment()" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                Lacak
            </button>
        </div>

        <p id="tracking_error_message" class="tracking-error hidden"></p>

        <div id="tracking_result" class="mt-4 hidden w-full">
            <p class="font-semibold">Status Pengiriman: <span id="shipment_status" class="font-normal"></span></p>
            <p class="font-semibold">Terakhir Diperbarui: <span id="last_tracked_at" class="font-normal"></span></p>
            <div id="user_map" class="mt-4 leaflet-map"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let userMap, userMarker;

    function initUserMap(lat, long) {
        if (!userMap) {
            userMap = L.map('user_map').setView([lat, long], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(userMap);
        }

        if (userMarker) {
            userMarker.setLatLng([lat, long]);
        } else {
            userMarker = L.marker([lat, long]).addTo(userMap)
                .bindPopup('Lokasi Kurir Saat Ini').openPopup();
        }

        userMap.setView([lat, long], 15);
        setTimeout(() => {
            userMap.invalidateSize();
        }, 100);
    }

    function trackShipment() {
        const trackingNumber = document.getElementById('tracking_number').value;
        const trackingResultDiv = document.getElementById('tracking_result');
        const shipmentStatusSpan = document.getElementById('shipment_status');
        const lastTrackedAtSpan = document.getElementById('last_tracked_at');
        const trackingErrorMessage = document.getElementById('tracking_error_message');

        trackingResultDiv.classList.add('hidden');
        trackingErrorMessage.classList.add('hidden');
        trackingErrorMessage.innerText = '';

        if (!trackingNumber) {
            trackingErrorMessage.innerText = 'Nomor resi wajib diisi.';
            trackingErrorMessage.classList.remove('hidden');
            return;
        }

        shipmentStatusSpan.innerText = 'Mencari data...';
        lastTrackedAtSpan.innerText = '';
        trackingResultDiv.classList.remove('hidden');

        fetch("{{ route('api.shipment_location') }}?tracking_number=" + trackingNumber)
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(data => {
                if (data.lat && data.long) {
                    initUserMap(data.lat, data.long);
                } else {
                    if (userMap) {
                        userMap.remove();
                        userMap = null;
                        userMarker = null;
                    }
                    document.getElementById('user_map').innerHTML = '<p class="text-center text-gray-500">Lokasi kurir belum tersedia atau tidak di-update.</p>';
                    document.getElementById('user_map').classList.add('leaflet-map');
                }

                shipmentStatusSpan.innerText = data.status || 'N/A';
                lastTrackedAtSpan.innerText = data.last_tracked_at || 'N/A';
                trackingResultDiv.classList.remove('hidden');
                trackingErrorMessage.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                if (userMap) {
                    userMap.remove();
                    userMap = null;
                    userMarker = null;
                }
                document.getElementById('user_map').innerHTML = '';
                document.getElementById('user_map').classList.add('leaflet-map');
                trackingResultDiv.classList.add('hidden');
                trackingErrorMessage.innerText = error.message || 'Terjadi kesalahan saat melacak pengiriman. Mohon periksa nomor resi Anda.';
                trackingErrorMessage.classList.remove('hidden');
            });
    }
</script>
@endpush

@endsection
