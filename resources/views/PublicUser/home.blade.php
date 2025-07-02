@extends('layouts.PublicUser')

@section('title', 'Home')

@section('header')
{{-- Memuat library peta Leaflet.js --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')

{{-- Tambahkan CSS di sini --}}
<style>
    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .tarif-result-animation {
        animation: fadeInScale 0.5s ease-out forwards;
    }

    /* Kelas untuk latar belakang kuning soft */
    .bg-soft-yellow-result {
        background-color: #FEF3C7; /* Tailwind yellow-200 */
        border-color: #FDE68A; /* Tailwind yellow-300 */
        color: #92400E; /* Tailwind yellow-800 for text, or a darker grey like #4B5563 (gray-700) */
    }

    /* Untuk teks total tarif agar lebih mencolok */
    .tarif-soft-yellow-result .text-lg.font-semibold span {
        color: #B45309; /* Tailwind yellow-800, atau bisa juga #1F2937 (gray-900) */
    }
</style>

<div class="w-full mx-auto relative">
    <div class="carousel w-full rounded-lg shadow-xl" id="autoSlider">
        <div id="autoSlide1" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/carousel1.jpg') }}" class="w-full object-cover h-64 md:h-80 lg:h-96" alt="Slider Image 1" />
            <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                <a href="#autoSlide3" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❮</a>
                <a href="#autoSlide2" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❯</a>
            </div>
        </div>
        <div id="autoSlide2" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/carousel2.jpg') }}" class="w-full object-cover h-64 md:h-80 lg:h-96" alt="Slider Image 2" />
            <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                <a href="#autoSlide1" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❮</a>
                <a href="#autoSlide3" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❯</a>
            </div>
        </div>
        <div id="autoSlide3" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/carousel3.png') }}" class="w-full object-cover h-64 md:h-80 lg:h-96" alt="Slider Image 3" />
            <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                <a href="#autoSlide2" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❮</a>
                <a href="#autoSlide1" class="btn btn-circle bg-base-100 bg-opacity-50 hover:bg-opacity-70 border-none">❯</a>
            </div>
        </div>
    </div>
</div>

<div class="flex justify-center mt-8">
    <div class="tabs tabs-boxed border border-black rounded-md overflow-hidden">
        <a onclick="selectTab('tracking')" id="tab-tracking-btn"
           class="tab tab-bordered bg-gradient-to-r from-yellow-400 to-yellow-300 text-black font-semibold border-r border-black">
            Live Tracking
        </a>
        <a onclick="selectTab('tarif')" id="tab-tarif-btn"
           class="tab tab-bordered bg-white text-black font-semibold">
            Cek Tarif
        </a>
    </div>
</div>

<div class="max-w-xl mx-auto mt-4 border border-black rounded-md shadow bg-white p-6">

    <div id="tab-tracking-content">
        <h3 class="font-bold mb-2">Lacak Pengiriman</h3>
        <div class="flex gap-2 flex-col md:flex-row">
            {{-- Menambahkan ID pada input dan tombol --}}
            <input type="text" id="public_tracking_number" placeholder="Masukkan nomor resi..."
                   class="input input-bordered w-full" />
            <button id="public_track_btn" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                Lacak
            </button>
        </div>
        {{-- Container untuk menampilkan hasil pelacakan --}}
        <div id="tracking-result-container" class="mt-4 hidden">
            <div id="public_map" class="mt-2 rounded-md" style="height: 300px; border: 1px solid #e2e8f0;"></div>
            <div id="tracking_status_info" class="mt-4 p-3 bg-gray-100 rounded-lg text-sm">
                <p><strong>Status:</strong> <span id="shipment_status">Memuat...</span></p>
                <p><strong>Terakhir Diperbarui:</strong> <span id="last_tracked_at">Memuat...</span></p>
            </div>
        </div>
        <div id="tracking-error-message" class="mt-4 text-red-600 text-sm font-medium"></div>
    </div>

    <div id="tab-tarif-content" class="hidden">
        <h3 class="font-bold mb-2">Cek Tarif</h3>
        <form action="{{ route('tarif.hitung') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <input type="text" name="asal" placeholder="Asal (contoh: Piayu)"
                       class="input input-bordered @error('asal') border-red-500 @enderror" value="{{ old('asal') }}" required />
                <input type="text" name="tujuan" placeholder="Tujuan (contoh: Batam Centre)"
                       class="input input-bordered @error('tujuan') border-red-500 @enderror" value="{{ old('tujuan') }}" required />
                <select class="select select-bordered @error('berat_kategori') border-red-500 @enderror" name="berat_kategori" required>
                    <option value="">Pilih Berat</option>
                    <option value="1-5" {{ old('berat_kategori') == '1-5' ? 'selected' : '' }}>1-5 Kg</option>
                    <option value="5-10" {{ old('berat_kategori') == '5-10' ? 'selected' : '' }}>5-10 Kg</option>
                </select>
                <button type="submit" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                    Cek Tarif
                </button>
            </div>
            @if ($errors->any())
                <div class="mt-2 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

        @if (session('tarif'))
            {{-- Menggunakan kelas baru untuk kuning soft --}}
            <div class="mt-4 p-4 bg-soft-yellow-result border rounded-lg tarif-result-container tarif-soft-yellow-result">
                <p class="font-bold">Hasil Perhitungan Tarif:</p>
                <p>Asal: <strong>{{ session('data_pengiriman.asal') }}</strong></p>
                <p>Tujuan: <strong>{{ session('data_pengiriman.tujuan') }}</strong></p>
                <p>Berat: <strong>{{ session('data_pengiriman.berat_kategori') }}</strong></p>
                <p>Jarak: <strong>{{ session('data_pengiriman.jarak') }} Km</strong></p>
                <p class="text-lg font-semibold">Total Tarif: <span class="font-extrabold">Rp {{ number_format(session('tarif'), 0, ',', '.') }}</span></p>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>
</div>


<div class="container mx-auto px-4 py-8 md:py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
        <div>
            <h2 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Selamat Datang di Dashboard SJ Courier Service!</h2>
            <p class="text-sm md:text-base text-gray-700 mb-3 md:mb-4">
                Melalui dashboard ini, kamu dapat dengan mudah membuat pengiriman baru, melacak status paket secara real-time, melakukan pembayaran baik secara tunai maupun non-tunai, serta melihat riwayat lengkap pengirimanmu.
            </p>
            <p class="text-sm md:text-base text-gray-700">
                Semua fitur yang tersedia dirancang untuk memberikan pengalaman pengiriman yang lebih cepat, aman, praktis, dan transparan, sehingga kamu dapat mengelola semua kebutuhan pengiriman dalam satu tempat dengan nyaman.
            </p>

            <div class="mt-4 md:mt-6">
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm md:btn-md">Daftar Sekarang</a>
            </div>
        </div>

        <div class="hidden md:flex justify-center">
            <img src="{{ asset('images/user/register.png') }}" alt="Delivery Person" class="max-w-sm">
        </div>
    </div>
</div>

<div class="py-10 bg-gray-100">
    <h2 class="text-2xl font-bold text-center mb-6 underline">Layanan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 md:px-12">
        <x-service-card
            title="Live Tracking"
            description="Tak perlu khawatir soal pengiriman. Pantau posisi paket secara real-time dan pastikan tiba tepat waktu!"
            button="Lacak Sekarang"
            {{-- Mengubah link menjadi aksi JavaScript untuk mengaktifkan tab tracking --}}
            onclick="selectTab('tracking'); document.getElementById('tab-tracking-btn').scrollIntoView({ behavior: 'smooth' });"
            link="javascript:void(0);"
            icon="{{ asset('images/user/1.png') }}"
        />

        <x-service-card
            title="Permintaan Pengiriman dan Pembayaran"
            description="Ajukan permintaan pengiriman dan lakukan pembayaran dengan mudah, cepat, dan aman dalam hitungan detik!"
            button="Buat Pengiriman"
            {{-- Pengguna tamu harus login untuk membuat pengiriman --}}
            link="{{ route('login') }}"
            icon="{{ asset('images/user/2.png') }}"
        />

        <x-service-card
            title="Cek Tarif"
            description="Lakukan cek tarif untuk menghitung estimasi biaya pengiriman suatu paket dari lokasi pengirim ke lokasi penerima!"
            button="Lihat"
            {{-- Mengubah link menjadi aksi JavaScript untuk mengaktifkan tab Cek Tarif --}}
            onclick="selectTab('tarif'); document.getElementById('tab-tarif-btn').scrollIntoView({ behavior: 'smooth' });"
            link="javascript:void(0);"
            icon="{{ asset('images/user/3.png') }}"
        />

        <x-service-card
            title="History Pengiriman"
            description="Lihat daftar pengirimanmu kapan saja! Riwayat lengkap & transparan untuk memastikan semuanya terkendali."
            button="Lihat History"
            {{-- Pengguna tamu harus login untuk melihat riwayat --}}
            link="{{ route('login') }}"
            icon="{{ asset('images/user/4.png') }}"
        />
    </div>
</div>


<div id="tips" class="container mx-auto px-4 py-8 md:py-12">
    <h2 class="text-xl md:text-2xl font-bold text-center mb-6 md:mb-8">Tips & Panduan Pengiriman</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
        <div class="hidden md:block">
            <img src="{{ asset('images/user/tips.png') }}" alt="Packing Tips" class="max-w-sm">
        </div>

        <div>
            <div class="prose max-w-none text-sm md:text-base">
                <p>
                    Sebelum mengirim paket, pastikan semua data pengiriman seperti alamat tujuan, nomor kontak penerima, dan detail barang sudah benar dan lengkap. Gunakan kemasan yang sesuai untuk menjaga keamanan barang selama proses pengiriman.
                </p>

                <p>
                    Hindari mengirim barang yang mudah rusak tanpa perlindungan tambahan seperti bubble wrap atau kardus tebal. Kamu juga bisa mengecek tarif terlebih dahulu dan memantau status pengiriman secara real-time langsung dari dashboard.
                </p>

                <p>
                    Setelah pengiriman berhasil, simpan nomor resi sebagai bukti dan referensi jika dibutuhkan di kemudian hari.
                </p>
            </div>

            <div class="mt-4 md:mt-6">
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm md:btn-md">Daftar dan Kirim Paket Sekarang</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Pass the PHP condition to a JavaScript variable. 1 is true, 0 is false.
    const shouldSelectTarifTab = {{ session('tarif') || session('error') || $errors->any() ? 'true' : 'false' }};   
    
    // Definisikan fungsi selectTab di scope global agar bisa diakses oleh onclick
    function selectTab(tab) {
        // Konten
        document.getElementById('tab-tracking-content').classList.add('hidden');
        document.getElementById('tab-tarif-content').classList.add('hidden');

        // Tombol Tab
        document.getElementById('tab-tracking-btn').classList.remove('bg-gradient-to-r', 'from-yellow-400', 'to-yellow-300');
        document.getElementById('tab-tracking-btn').classList.add('bg-white');

        document.getElementById('tab-tarif-btn').classList.remove('bg-gradient-to-r', 'from-yellow-400', 'to-yellow-300');
        document.getElementById('tab-tarif-btn').classList.add('bg-white');

        // Tampilkan tab sesuai yang dipilih
        document.getElementById(`tab-${tab}-content`).classList.remove('hidden');
        document.getElementById(`tab-${tab}-btn`).classList.add('bg-gradient-to-r', 'from-yellow-400', 'to-yellow-300');
        document.getElementById(`tab-${tab}-btn`).classList.remove('bg-white');
    }

    document.addEventListener('DOMContentLoaded', function() {
        let publicMap, publicMarker;
        let trackingInterval;

        // --- Logika Carousel ---
        let currentSlide = 1;
        const totalSlides = 3;
        function moveToNextSlide() {
            currentSlide = currentSlide >= totalSlides ? 1 : currentSlide + 1;
            const slideElement = document.querySelector(`#autoSlide${currentSlide}`);
            if (slideElement) {
                slideElement.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
            }
        }
        setInterval(moveToNextSlide, 20000);

        // --- Logika Tab Tarif saat load halaman ---
        if (shouldSelectTarifTab) {
            selectTab('tarif');
            const tarifResultDiv = document.querySelector('.tarif-result-container');
            if (tarifResultDiv) {
                tarifResultDiv.classList.add('tarif-result-animation');
            }
        }

        // Fungsi untuk inisialisasi atau update peta
        function initPublicMap(lat, long) {
            try {
            const mapContainer = document.getElementById('public_map');
            if (!mapContainer) return;
            // Pastikan Leaflet sudah di-load
            } catch (error) {
                console.error('Map initializing error:', error);
                return;
            }
            // Jika peta belum ada, buat baru
            if (!publicMap) {
                publicMap = L.map('public_map').setView([lat, long], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(publicMap);
            }

            // Jika marker sudah ada, pindahkan. Jika belum, buat baru.
            if (publicMarker) {
                publicMarker.setLatLng([lat, long]);
            } else {
                const courierIcon = L.icon({
                    iconUrl: '{{ asset("images/user/courier-icon.png") }}', // Ganti dengan path ikon kurir Anda
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });
                publicMarker = L.marker([lat, long], {icon: courierIcon}).addTo(publicMap)
                    .bindPopup('Posisi Terkini Paket').openPopup();
            }

            // Arahkan peta ke posisi marker
            publicMap.setView([lat, long], 15);
        }

        // Fungsi utama untuk melacak pengiriman
        async function trackShipment() {
            const trackingNumber = document.getElementById('public_tracking_number').value.trim();
            const resultContainer = document.getElementById('tracking-result-container');
            const errorContainer = document.getElementById('tracking-error-message');
            const trackBtn = document.getElementById('public_track_btn');

            if (!trackingNumber) {
                errorContainer.textContent = 'Nomor resi tidak boleh kosong.';
                resultContainer.classList.add('hidden');
                return;
            }

            // Hentikan pelacakan sebelumnya jika ada
            window.addEventListener('beforeunload', function() {
                if (trackingInterval) {
                    clearInterval(trackingInterval);
                }
            });

            trackBtn.disabled = true;
            trackBtn.textContent = 'Melacak...';
            errorContainer.textContent = '';

            const fetchLocation = async () => {
                try {
                    const response = await fetch(`{{ route('api.shipment_location') }}?tracking_number=${trackingNumber}`);
                    const data = await response.json();

                    if (!response.ok) {
                        errorContainer.textContent = data.message || 'Gagal mengambil data.';
                        resultContainer.classList.add('hidden');
                        clearInterval(trackingInterval);
                        trackBtn.disabled = false;
                        trackBtn.textContent = 'Lacak';
                        return;
                    }

                    if (data.lat && data.long) {
                        resultContainer.classList.remove('hidden');
                        initPublicMap(data.lat, data.long);
                        document.getElementById('shipment_status').textContent = data.status || 'N/A';
                        document.getElementById('last_tracked_at').textContent = data.last_tracked_at || 'N/A';
                    } else {
                        errorContainer.textContent = 'Lokasi untuk pengiriman ini belum tersedia.';
                        resultContainer.classList.add('hidden');
                    }

                } catch (error) {
                    console.error('Fetch error:', error);
                    errorContainer.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    clearInterval(trackingInterval);
                }
            };

            await fetchLocation(); // Panggil pertama kali
            trackingInterval = setInterval(fetchLocation, 15000); // Ulangi setiap 15 detik
            trackBtn.disabled = false;
            trackBtn.textContent = 'Lacak';
        }

        document.getElementById('public_track_btn').addEventListener('click', trackShipment);
    });
</script>
@endpush