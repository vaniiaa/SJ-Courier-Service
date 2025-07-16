@extends('layouts.kurir_page')
@section('title', 'Live Tracking')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="absolute top-32 left-0 right-0 px-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md text-center">
        <x-alert type="warning" id="trackingAlert" class="hidden">
            Silakan pilih pengiriman yang akan diantar.
        </x-alert>
        <h1 class="text-xl font-bold mb-4">Aktifkan Live Tracking Kurir</h1>
        
        <div class="flex flex-col md:flex-row gap-2 mb-4">
            <select id="tracking_number_select" class="select select-bordered w-full bg-gray-100 rounded-md shadow" disabled>
                <option value="">Memuat daftar pengiriman...</option>
            </select>

            <button onclick="startTracking()" id="startBtn"
                class="btn bg-yellow-400 hover:bg-yellow-500 text-black font-bold shadow-md">
                Mulai Tracking
            </button>
            <button onclick="stopTracking()" id="stopBtn"
                class="btn btn-error text-white font-bold shadow-md hidden">
                Hentikan Tracking
            </button>
        </div>

        <div id="status" class="mt-4 text-sm text-gray-600 p-3 bg-gray-100 rounded-lg">
            <p>Status: <span id="status_message" class="font-semibold">Belum aktif</span></p>
            <p>Lokasi Terakhir Dikirim: <span id="last_sent_time" class="font-semibold">N/A</span></p>
        </div>

        <div id="map" class="mt-6 rounded-md border" style="height: 300px;">
            <div class="flex items-center justify-center h-full bg-gray-50 text-gray-400">Peta akan muncul di sini setelah tracking dimulai</div>
        </div>
    </div>
</div>

<script>
    let map, marker, watchId, trackingInterval;
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const trackingSelect = document.getElementById('tracking_number_select');
    const statusMessage = document.getElementById('status_message');
    const lastSentTime = document.getElementById('last_sent_time');

    function showAlert(alertId) {
    const alertDiv = document.getElementById(alertId);
    if (alertDiv) {
        alertDiv.classList.remove('hidden'); // Cukup tampilkan
        setTimeout(() => hideAlert(alertId), 5000); // Sembunyikan setelah 5 detik
    }
}

    function hideAlert(alertId) {
        const alertDiv = document.getElementById(alertId);
        if (alertDiv) {
            alertDiv.classList.add('hidden');
        }
    }

    function initMap(lat, long) {
        if (!map) {
            map = L.map('map').setView([lat, long], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            document.querySelector('#map > .flex')?.remove(); // Hapus placeholder
        }

        if (marker) {
            marker.setLatLng([lat, long]);
        } else {
            marker = L.marker([lat, long]).addTo(map)
                .bindPopup('Lokasi Kurir Saat Ini').openPopup();
        }

        map.setView([lat, long], 15);
    }

    function sendLocation(trackingNumber, lat, long) {
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
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => Promise.reject(err));
            }
            return res.json();
        })
        .then(data => {
            statusMessage.textContent = 'Aktif';
            statusMessage.className = 'font-semibold text-green-600';
            lastSentTime.textContent = new Date().toLocaleTimeString('id-ID');
        })
        .catch(err => {
            console.error('Gagal kirim lokasi:', err);
            statusMessage.textContent = `Gagal: ${err.message || 'Error tidak diketahui'}`;
            statusMessage.className = 'font-semibold text-red-600';
            stopTracking(); // Hentikan jika ada error dari server (misal: resi sudah selesai)
        });
    }

   function startTracking() {
        const trackingNumber = trackingSelect.value;

        if (!trackingNumber || trackingNumber === "") {
            showAlert('trackingAlert'); // <-- Kode baru
            return;
        }

        if (!navigator.geolocation) {
            showAlert('trackingAlert');
            return;
        }


        startBtn.disabled = true;
        startBtn.textContent = 'Memulai...';
        trackingSelect.disabled = true;
        statusMessage.textContent = 'Mendapatkan lokasi awal...';

        // Dapatkan lokasi pertama kali
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const long = position.coords.longitude;

            initMap(lat, long);
            sendLocation(trackingNumber, lat, long); // Kirim lokasi pertama

            // Invalidate map size after it becomes visible or its container changes size
            // This is crucial for Leaflet maps inside hidden or dynamic containers
            // Set interval untuk mengirim lokasi setiap 15 detik
            trackingInterval = setInterval(() => {
                navigator.geolocation.getCurrentPosition(pos => {
                    sendLocation(trackingNumber, pos.coords.latitude, pos.coords.longitude);
                    initMap(pos.coords.latitude, pos.coords.longitude);
                }, error => {
                    console.error('Gagal mendapatkan lokasi untuk interval:', error);
                    statusMessage.textContent = 'Gagal mendapatkan lokasi.';
                    statusMessage.className = 'font-semibold text-red-600';
                });
            }, 15000); // 15 detik

            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            startBtn.textContent = 'Mulai Tracking';
        }, function(error) {
            alert('Gagal mendapatkan lokasi awal: ' + error.message);
            startBtn.disabled = false;
            startBtn.textContent = 'Mulai Tracking';
            trackingSelect.disabled = false;
        }, {
            enableHighAccuracy: true
        });
    }

    function stopTracking() {
        if (trackingInterval) clearInterval(trackingInterval);
        trackingInterval = null;
        startBtn.disabled = false;
        trackingSelect.disabled = false;
        startBtn.classList.remove('hidden');
        stopBtn.classList.add('hidden');
        statusMessage.textContent = 'Tidak aktif';
        statusMessage.className = 'font-semibold text-gray-500';
        lastSentTime.textContent = 'N/A';
    }

    // Fungsi untuk memuat daftar pengiriman saat halaman dibuka
    function loadActiveShipments() {
        fetch("{{ route('kurir.api.active_shipments') }}")
            .then(res => res.json())
            .then(data => {
                trackingSelect.innerHTML = '<option value="" disabled selected>Pilih Pengiriman</option>'; // Reset
                if (data.shipments && data.shipments.length > 0) {
                    data.shipments.forEach(shipment => {
                        const option = document.createElement('option');
                        option.value = shipment.tracking_number;
                        // Tampilkan info yang berguna untuk kurir
                        option.textContent = `Resi: ${shipment.tracking_number} - Penerima: ${shipment.order.receiverName}`;
                        trackingSelect.appendChild(option);
                    });
                    trackingSelect.disabled = false;
                } else {
                    trackingSelect.innerHTML = '<option value="">Tidak ada pengiriman aktif</option>';
                }
            })
            .catch(err => {
                console.error('Gagal memuat pengiriman:', err);
                trackingSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', loadActiveShipments);
</script>
@endsection