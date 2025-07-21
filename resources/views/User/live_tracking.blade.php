@section('title', 'Live Tracking')

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

            {{-- Kotak Pencarian dan Hasil Live Tracking --}}
        <div class="absolute top-32 left-0 right-0 px-4">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="font-bold mb-2">Lacak Pengiriman Anda</h3>
                <div class="flex gap-2 flex-col md:flex-row">
                    <input type="text" id="user_tracking_number" placeholder="Masukkan Nomor Resi Anda"
                           class="input input-bordered w-full" />
                    <button id="track_btn" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                        Lacak
                    </button>
                    </div>
                    
                    {{-- Kontainer untuk menampilkan semua hasil --}} 
                    <div id="tracking_result" class="mt-4 hidden">
                        {{-- Hasil akan dirender oleh JavaScript di sini --}}
                    </div>

                    <p id="tracking_error_message" class="text-red-600 font-semibold mt-2 hidden"></p>
                </div>
        </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let userMap, userMarker, trackingInterval;
        const POLLING_INTERVAL_MS = 15000; // 15 detik

        const trackBtn = document.getElementById('track_btn');
        const trackingInput = document.getElementById('user_tracking_number');
        const trackingResultDiv = document.getElementById('tracking_result');
        const trackingErrorMessage = document.getElementById('tracking_error_message');

        function initUserMap(lat, long) {
            // Pastikan div peta ada sebelum inisialisasi
            if (!document.getElementById('user_map')) return;

            if (!userMap) {
                userMap = L.map('user_map').setView([lat, long], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(userMap);
            }
            if (userMarker) {
                userMarker.setLatLng([lat, long]);
            } else {
                const courierIcon = L.icon({
                    iconUrl: '{{ asset("images/user/courier-icon.png") }}', // Pastikan path icon benar
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });
                userMarker = L.marker([lat, long], { icon: courierIcon }).addTo(userMap)
                    .bindPopup('Lokasi Kurir Saat Ini').openPopup();
            }
            userMap.setView([lat, long], 15);
        }

        function stopCurrentTracking() {
            if (trackingInterval) {
                clearInterval(trackingInterval);
                trackingInterval = null;
            }
        }
        
        function renderShipmentDetails(data) {
            const details = data.details;
            const message = data.message || 'Detail Pengiriman:';
            
            return `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="mb-4">
                        <p><strong>Status Pengiriman:</strong> <span class="font-normal badge ${getBadgeClass(data.shipment_status)}">${data.shipment_status || 'N/A'}</span></p>
                        <p><strong>Terakhir Diperbarui:</strong> <span class="font-normal">${data.updated_at || 'N/A'}</span></p>
                    </div>
                    <div class="p-4 border rounded-md bg-white">
                        <h4 class="font-semibold mb-2 text-center">${message}</h4>
                        <div class="divider my-1"></div>
                        <div class="text-sm space-y-1">
                            <p><strong>Penerima:</strong> ${details.receiver_name}</p>
                            <p><strong>Alamat:</strong> ${details.receiver_address}</p>
                            <p><strong>Barang:</strong> ${details.item_type} (${details.weight_kg} Kg)</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function renderMap(data) {
             return `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="mb-2">
                        <p><strong>Status:</strong> <span class="font-normal badge ${getBadgeClass(data.shipment_status)}">${data.shipment_status || 'N/A'}</span></p>
                        <p><strong>Terakhir Diperbarui:</strong> <span class="font-normal">${data.updated_at || 'N/A'}</span></p>
                    </div>
                    <div id="user_map" class="mt-2 rounded-md border" style="height: 400px;"></div>
                </div>
            `;
        }

        function getBadgeClass(status) {
        if (!status) return 'badge-info'; // Default
        const s = status.toLowerCase().trim();
        switch (s) {
            case 'menunggu konfirmasi': return 'badge-ghost';
            case 'kurir ditugaskan': return 'badge-info';
            case 'kurir menuju lokasi penjemputan': return 'badge-warning';
            case 'paket telah di-pickup': return 'badge-primary';
            case 'dalam perjalanan ke penerima': return 'badge-secondary';
            case 'pesanan selesai': return 'badge-success';
            default: return 'badge-info';
            }
        }

        function trackShipment() {
            const trackingNumber = trackingInput.value.trim();
            stopCurrentTracking();

            // Reset UI
            trackingErrorMessage.classList.add('hidden');
            trackingResultDiv.classList.add('hidden');
            trackingResultDiv.innerHTML = ''; // Kosongkan hasil sebelumnya
            if (userMap) {
                userMap.remove();
                userMap = null;
                userMarker = null;
            }

            if (!trackingNumber) {
                trackingErrorMessage.innerText = 'Nomor resi wajib diisi.';
                trackingErrorMessage.classList.remove('hidden');
                return;
            }

            // Tampilkan spinner
            trackBtn.disabled = true;
            trackBtn.innerHTML = `<span class="loading loading-spinner loading-xs"></span> Melacak...`;

            const fetchLocation = async () => {
                // Hanya tampilkan spinner pada fetch pertama
                if (!trackingResultDiv.classList.contains('hidden')) {
                    trackBtn.disabled = true;
                    trackBtn.innerHTML = 'Lacak';
                }

                try {
                    const response = await fetch(`{{ route('api.shipment_location') }}?tracking_number=${trackingNumber}`);
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal mengambil data pengiriman.');
                    }
                    
                    trackingResultDiv.classList.remove('hidden');

                    if (data.status === 'finished' || data.tracking_status === 'inactive') {
                        stopCurrentTracking();
                        trackingResultDiv.innerHTML = renderShipmentDetails(data);
                    } 
                    else if (data.tracking_status === 'active' && data.lat && data.long) {
                        // Hanya render ulang peta jika belum ada
                        if (!userMap) {
                            trackingResultDiv.innerHTML = renderMap(data);
                            initUserMap(data.lat, data.long);
                        } else {
                            // Cukup update marker dan view
                            userMarker.setLatLng([data.lat, data.long]);
                            userMap.panTo([data.lat, data.long]);
                        }
                        
                        // Mulai polling jika belum berjalan
                        if (!trackingInterval) {
                            trackingInterval = setInterval(fetchLocation, POLLING_INTERVAL_MS);
                        }
                    }

                } catch (error) {
                    stopCurrentTracking();
                    trackingResultDiv.classList.add('hidden');
                    trackingErrorMessage.innerText = error.message;
                    trackingErrorMessage.classList.remove('hidden');
                } finally {
                    if (!trackingInterval) {
                        trackBtn.disabled = false;
                        trackBtn.innerHTML = 'Lacak';
                    }
                }
            };

            fetchLocation();
        }

        trackBtn.addEventListener('click', trackShipment);
        trackingInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                trackShipment();
            }
        });
    });
    </script>
    @endpush
</x-app-layout>