{{-- resources/views/User/live_tracking.blade.php --}}

<x-app-layout> {{-- Panggil ini sebagai komponen! --}}
    {{-- Konten halaman ini akan secara otomatis menjadi $slot di dalam x-app-layout --}}

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* Style untuk pesan error tracking, jika belum ada di CSS global Anda */
        .tracking-error {
            color: #ef4444; /* Tailwind red-500 */
            font-weight: bold;
            margin-top: 0.5rem;
        }

        /* Pastikan peta memiliki tinggi yang cukup */
        .leaflet-map {
            height: 300px;
            border-radius: 0.5rem; /* rounded-md */
        }
    </style>

    <div class="relative">
        {{-- Breadcrumbs/Background Kuning --}}
        {{-- Ini adalah div yang akan memberikan background kuning penuh lebar --}}
        {{-- Jika x-app-layout sudah memiliki background kuning ini, Anda bisa menghapusnya dari sini --}}
        <div class="bg-[rgba(255,165,0,0.75)] p-6 shadow-md h-40 absolute top-0 left-1/2 transform -translate-x-1/2 z-0" 
             style="width: 100vw; margin-left: -50vw; left: 50%;"></div>

        {{-- Konten Utama Halaman --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 py-8">
            {{-- Judul Halaman --}}
            <h1 class="text-2xl font-bold text-black mb-8">Live Tracking Pengiriman</h1>

            {{-- Kotak Pencarian dan Hasil Live Tracking --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold mb-2">Lacak Pengiriman Anda</h3>
                <div class="flex gap-2 flex-col md:flex-row">
                    <input type="text" id="user_tracking_number" placeholder="Masukkan Nomor Resi Anda"
                           class="input input-bordered w-full" />
                    <button onclick="trackShipment()" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                        Lacak
                    </button>
                </div>
                <div id="tracking_result" class="mt-4 hidden">
                    <p class="font-semibold">Status Pengiriman: <span id="shipment_status" class="font-normal"></span></p>
                    <p class="font-semibold">Terakhir Diperbarui: <span id="last_tracked_at" class="font-normal"></span></p>
                    <div id="user_map" class="mt-4 leaflet-map"></div>
                </div>
                <p id="tracking_error_message" class="tracking-error hidden"></p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let userMap, userMarker;

        // Fungsi untuk inisialisasi atau memperbarui peta user
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
            // Memastikan peta di-resize jika div-nya sebelumnya hidden
            setTimeout(() => {
                userMap.invalidateSize();
            }, 100);
        }

        // Fungsi untuk melacak pengiriman
        function trackShipment() {
            const trackingNumber = document.getElementById('user_tracking_number').value;
            const trackingResultDiv = document.getElementById('tracking_result');
            const shipmentStatusSpan = document.getElementById('shipment_status');
            const lastTrackedAtSpan = document.getElementById('last_tracked_at');
            const trackingErrorMessage = document.getElementById('tracking_error_message');

            // Sembunyikan hasil sebelumnya dan pesan error
            trackingResultDiv.classList.add('hidden');
            trackingErrorMessage.classList.add('hidden');
            trackingErrorMessage.innerText = '';

            if (!trackingNumber) {
                trackingErrorMessage.innerText = 'Nomor resi wajib diisi.';
                trackingErrorMessage.classList.remove('hidden');
                return;
            }

            // Tampilkan loading atau pesan bahwa sedang melacak
            shipmentStatusSpan.innerText = 'Mencari data...';
            lastTrackedAtSpan.innerText = '';
            trackingResultDiv.classList.remove('hidden'); // Tampilkan div hasil walaupun masih loading

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
                        // Jika tidak ada lat/long (mungkin belum di-track oleh kurir)
                        if (userMap) {
                            userMap.remove(); // Hapus peta jika sudah ada
                            userMap = null;
                            userMarker = null;
                        }
                        document.getElementById('user_map').innerHTML = '<p class="text-center text-gray-500">Lokasi kurir belum tersedia atau tidak di-update.</p>';
                        // Tambahkan kelas leaflet-map kembali jika dihilangkan, agar styling tetap ada
                        document.getElementById('user_map').classList.add('leaflet-map');
                    }

                    shipmentStatusSpan.innerText = data.status || 'N/A';
                    lastTrackedAtSpan.innerText = data.last_tracked_at || 'N/A';
                    trackingResultDiv.classList.remove('hidden');
                    trackingErrorMessage.classList.add('hidden'); // Pastikan error disembunyikan jika sukses
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (userMap) {
                        userMap.remove(); // Hapus peta jika ada error
                        userMap = null;
                        userMarker = null;
                    }
                    document.getElementById('user_map').innerHTML = ''; // Kosongkan area peta
                    // Tambahkan kelas leaflet-map kembali jika dihilangkan, agar styling tetap ada
                    document.getElementById('user_map').classList.add('leaflet-map');
                    trackingResultDiv.classList.add('hidden'); // Sembunyikan div hasil jika ada error
                    trackingErrorMessage.innerText = error.message || 'Terjadi kesalahan saat melacak pengiriman. Mohon periksa nomor resi Anda.';
                    trackingErrorMessage.classList.remove('hidden');
                });
        }
    </script>
    @endpush
</x-app-layout>