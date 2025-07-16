<x-app-layout>
    @section('title', 'Form Pengiriman')
            {{-- Notifikasi Error/Sukses --}}
            @if(session('error'))
                <div role="alert" class="alert alert-error mb-6 shadow-lg"><span>{{ session('error') }}</span></div>
            @endif
            @if ($errors->any())
                <div role="alert" class="alert alert-warning mb-6 shadow-lg">
                    <div>
                        <h3 class="font-bold">Oops! Terjadi kesalahan.</h3>
                        <ul class="text-xs list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            <div class="max-w-2xl mx-auto left-0 right-0 px-4 p-6">
            <form id="shipmentForm" method="POST" action="{{ route('user.store_pengiriman') }}">
                @csrf

                <!-- ================================== -->
                <!--       BAGIAN 1: DATA PENGIRIM      -->
                <!-- ================================== -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">1. Data Pengirim (Customer)</h2>
                    
                    {{-- Menampilkan Nama, No. HP, dan Email Pengirim (Read-only) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Pengirim</label>
                            <input type="text" value="{{ Auth::user()->name }}" class="input input-bordered w-full bg-gray-100 cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">No. Handphone</label>
                            <input type="text" value="{{ Auth::user()->phone }}" class="input input-bordered w-full bg-gray-100 cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" class="input input-bordered w-full bg-gray-100 cursor-not-allowed" readonly>
                        </div>
                    </div>

                    {{-- Dropdown untuk Alamat Tersimpan --}}
                    @if($savedAddresses->isNotEmpty())
                    <div class="form-control w-full mb-4">
                        <label for="saved_address" class="block text-sm font-medium text-gray-700">Gunakan Alamat Tersimpan</label>
                        <select id="saved_address" class="select select-bordered w-full">
                            <option value="">-- Pilih alamat yang sudah disimpan --</option>
                            @foreach($savedAddresses as $address)
                                <option value="{{ json_encode($address) }}" @if(old('pickupAddress') == $address->address) selected @endif>
                                    {{ $address->label }} - {{ Str::limit($address->address, 50) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    {{-- Alamat Penjemputan Baru / Manual --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div>
                            <label for="pickupAddress" class="block text-sm font-medium text-gray-700">Alamat Penjemputan</label>
                            <input type="text" id="pickupAddress" name="pickupAddress" placeholder="Isi alamat baru atau pilih dari daftar di atas" value="{{ old('pickupAddress', Auth::user()->address) }}" class="input input-bordered w-full @error('pickupAddress') input-error @enderror" required>
                            @error('pickupAddress')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="pickupKecamatan" class="block text-sm font-medium text-gray-700">Kecamatan Penjemputan</label>
                            <select id="pickupKecamatan" name="pickupKecamatan" class="select select-bordered w-full @error('pickupKecamatan') select-error @enderror" required>
                                <option disabled selected value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan }}" @if(old('pickupKecamatan') == $kecamatan) selected @endif>{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                            @error('pickupKecamatan')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Titik Penjemputan di Peta</label>
                        <div id="pickupMap" class="leaflet-map"></div>
                        <input type="hidden" name="pickupLatitude" id="pickupLatitude" value="{{ old('pickupLatitude') }}">
                        <input type="hidden" name="pickupLongitude" id="pickupLongitude" value="{{ old('pickupLongitude') }}">
                    </div>
                </div>

                <!-- ================================= -->
                <!--       BAGIAN 2: DATA PENERIMA     -->
                <!-- ================================= -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">2. Data Penerima</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="receiverName" class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                            <input type="text" id="receiverName" name="receiverName" placeholder="Masukkan Nama Penerima" value="{{ old('receiverName') }}" class="input input-bordered w-full @error('receiverName') input-error @enderror" required>
                            @error('receiverName')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="receiverPhoneNumber" class="block text-sm font-medium text-gray-700">No. Handphone</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-yellow-400 text-black rounded-l-md">+62</span>
                                <input type="text" id="receiverPhoneNumber" name="receiverPhoneNumber" placeholder="81234567890" value="{{ old('receiverPhoneNumber') }}" class="input input-bordered w-full rounded-l-none @error('receiverPhoneNumber') input-error @enderror" required>
                            </div>
                            @error('receiverPhoneNumber')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="receiverAddress" class="block text-sm font-medium text-gray-700">Alamat Penerima</label>
                            <input type="text" id="receiverAddress" name="receiverAddress" placeholder="Masukkan Alamat lengkap penerima" value="{{ old('receiverAddress') }}" class="input input-bordered w-full @error('receiverAddress') input-error @enderror" required>
                            @error('receiverAddress')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="receiverKecamatan" class="block text-sm font-medium text-gray-700">Kecamatan Tujuan</label>
                            <select id="receiverKecamatan" name="receiverKecamatan" class="select select-bordered w-full @error('receiverKecamatan') select-error @enderror" required>
                                <option disabled selected value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan }}" @if(old('receiverKecamatan') == $kecamatan) selected @endif>{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                            @error('receiverKecamatan')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Titik Tujuan di Peta</label>
                        <div id="receiverMap" class="leaflet-map"></div>
                        <input type="hidden" name="receiverLatitude" id="receiverLatitude" value="{{ old('receiverLatitude') }}">
                        <input type="hidden" name="receiverLongitude" id="receiverLongitude" value="{{ old('receiverLongitude') }}">
                    </div>
                </div>

                <!-- ================================= -->
                <!--     BAGIAN 3: INFORMASI BARANG    -->
                <!-- ================================= -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">3. Informasi Barang</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="itemType" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                            <input type="text" id="itemType" name="itemType" placeholder="Contoh: Pakaian, Dokumen" value="{{ old('itemType') }}" class="input input-bordered w-full @error('itemType') input-error @enderror" required>
                            @error('itemType')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="weightKG" class="block text-sm font-medium text-gray-700">Berat Barang (Kg)</label>
                            <input type="number" step="0.1" min="0.1" id="weightKG" name="weightKG" placeholder="Contoh: 1.5" value="{{ old('weightKG') }}" class="input input-bordered w-full @error('weightKG') input-error @enderror" required>
                            @error('weightKG')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-control w-full mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan untuk Kurir (Opsional)</label>
                        <textarea id="notes" name="notes" placeholder="Contoh: Barang mudah pecah, titip di security." class="textarea textarea-bordered h-20">{{ old('notes') }}</textarea>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <button type="submit" class="btn bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-8 py-2 rounded-lg shadow-md w-full md:w-auto">Lanjutkan ke Ringkasan</button>
                </div>
            </form>
            </div>
    @push('scripts')
        <script>
            // Variabel global untuk menyimpan objek peta dan marker agar bisa diakses
            window.maps = {};

            document.addEventListener('DOMContentLoaded', function () {
                const batamCenter = [1.12, 104.03];
                
                function initMap(mapId, latInputId, lngInputId, addressInputId, defaultCoords) {
                    const latInput = document.getElementById(latInputId);
                    const lngInput = document.getElementById(lngInputId);
                    
                    let initialCoords = defaultCoords;
                    // Gunakan data dari 'old' jika ada, jika tidak gunakan default
                    if(latInput.value && lngInput.value) {
                        initialCoords = [parseFloat(latInput.value), parseFloat(lngInput.value)];
                    } else {
                        latInput.value = defaultCoords[0].toFixed(7);
                        lngInput.value = defaultCoords[1].toFixed(7);
                    }

                    const map = L.map(mapId).setView(initialCoords, 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19, attribution: '&copy; OpenStreetMap'
                    }).addTo(map);
                    
                    let marker = L.marker(initialCoords, { draggable: true }).addTo(map);

                    marker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        latInput.value = pos.lat.toFixed(7);
                        lngInput.value = pos.lng.toFixed(7);
                        map.panTo(pos);
                    });

                    // Simpan objek map dan marker ke variabel global untuk diakses nanti
                    window.maps[mapId] = { map: map, marker: marker };
                }

                // Inisialisasi Peta
                initMap('pickupMap', 'pickupLatitude', 'pickupLongitude', 'pickupAddress', batamCenter);
                initMap('receiverMap', 'receiverLatitude', 'receiverLongitude', 'receiverAddress', [batamCenter[0] + 0.03, batamCenter[1] + 0.03]);

                // Event listener untuk dropdown alamat tersimpan
                const savedAddressDropdown = document.getElementById('saved_address');
                if (savedAddressDropdown) {
                    savedAddressDropdown.addEventListener('change', function() {
                        if (!this.value) return; // Jika memilih opsi "-- Pilih alamat --"
                        try {
                            const selectedData = JSON.parse(this.value);
                            document.getElementById('pickupAddress').value = selectedData.address;
                            document.getElementById('pickupLatitude').value = selectedData.latitude;
                            document.getElementById('pickupLongitude').value = selectedData.longitude;

                            // Update peta pickup
                            const pickupMapInfo = window.maps['pickupMap'];
                            if (pickupMapInfo && selectedData.latitude && selectedData.longitude) {
                                const lat = parseFloat(selectedData.latitude);
                                const lng = parseFloat(selectedData.longitude);
                                const newLatLng = new L.LatLng(lat, lng);
                                pickupMapInfo.marker.setLatLng(newLatLng);
                                pickupMapInfo.map.setView(newLatLng, 15);
                            }
                        } catch (e) {
                            console.error("Gagal mem-parsing data alamat tersimpan:", e);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
