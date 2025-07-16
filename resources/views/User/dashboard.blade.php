<x-app-layout>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

    /* Style untuk pesan error tracking */
    .tracking-error {
        color: #ef4444; /* Tailwind red-500 */
        font-weight: bold;
        margin-top: 0.5rem;
    }

    html, body {
        margin: 0;
        padding: 0;
        overflow-x: hidden; 
    }

    .navbar { 
        margin-bottom: 0 !important; 
    }
   
    header {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Carousel Full Width */
    .carousel-full-width {
        width: 100vw; 
        position: relative;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 0 !important; 
        padding-top: 0 !important;
        border: none;
        box-shadow: none;
    }

    /* Mengatur tinggi gambar carousel untuk responsivitas */
    .carousel-item img {
        width: 100%;
        height: auto; 
        object-fit: contain; 
        background-color: transparent; 
        display: block;
    }

    @media (min-width: 640px) { /* sm */
        .carousel-item img {
            height: auto;
        }
    }

    @media (min-width: 768px) { /* md */
        .carousel-item img {
            height: auto;
        }
    }

    @media (min-width: 1024px) { /* lg */
        .carousel-item img {
            height: auto;
        }
    }
    @media (min-width: 1280px) { /* xl */
        .carousel-item img {
            height: auto;
        }
    }

    .carousel-item .absolute.px-4.md\:px-6 {
        padding-left: 1rem; /* setara dengan px-4 */
        padding-right: 1rem; /* setara dengan px-4 */
    }

    /* Untuk mobile*/
    .carousel {
        border-radius: 0;
        box-shadow: none;
        margin-top: 0 !important; 
    }
    @media (min-width: 768px) { 
        .carousel {
            border-radius: 0.5rem; /* rounded-lg */
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); /* shadow-xl */
        }
    }
</style>

<div class="carousel-full-width"> 
    <div class="carousel w-full" id="autoSlider">
        <div id="autoSlide1" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/fixcarousel4.png') }}" alt="Slider Image 1" />
            <div class="absolute flex justify-between w-full top-1/2 transform -translate-y-1/2 px-4 md:px-6"> {{-- Adjust px for mobile --}}
                <a href="#autoSlide3" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❮</a>
                <a href="#autoSlide2" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❯</a>
            </div>
        </div>
        <div id="autoSlide2" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/fixcarousel5.png') }}" alt="Slider Image 2" />
            <div class="absolute flex justify-between w-full top-1/2 transform -translate-y-1/2 px-4 md:px-6"> {{-- Adjust px for mobile --}}
                <a href="#autoSlide1" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❮</a>
                <a href="#autoSlide3" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❯</a>
            </div>
        </div>
        <div id="autoSlide3" class="carousel-item relative w-full">
            <img src="{{ asset('images/kurir/fixcarousel6.png') }}" alt="Slider Image 3" />
            <div class="absolute flex justify-between w-full top-1/2 transform -translate-y-1/2 px-4 md:px-6"> {{-- Adjust px for mobile --}}
                <a href="#autoSlide2" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❮</a>
                <a href="#autoSlide1" class="btn btn-circle bg-base-100 bg-opacity-50 border-none">❯</a>
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
            <input type="text" id="user_tracking_number" placeholder="Masukkan Nomor Resi Anda"
                   class="input input-bordered w-full" />
            <button onclick="trackShipment()" class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
                Lacak
            </button>
        </div>
        <div id="tracking_result" class="mt-4 hidden">
            <p class="font-semibold">Status Pengiriman: <span id="shipment_status" class="font-normal"></span></p>
            <p class="font-semibold">Terakhir Diperbarui: <span id="last_tracked_at" class="font-normal"></span></p>
            <div id="user_map" class="mt-4 rounded-md" style="height: 300px;"></div>
        </div>
        <p id="tracking_error_message" class="tracking-error hidden"></p>
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
            link="{{ route('user.live_tracking') }}"
            icon="{{ asset('images/user/1.png') }}"
        />

        <x-service-card
            title="Permintaan Pengiriman dan Pembayaran"
            description="Ajukan permintaan pengiriman dan lakukan pembayaran dengan mudah, cepat, dan aman dalam hitungan detik!"
            button="Buat Pengiriman"
            link="{{ route('user.form_pengiriman') }}"
            icon="{{ asset('images/user/2.png') }}"
        />

        <x-service-card
            title="Cek Tarif"
            description="Lakukan cek tarif untuk menghitung estimasi biaya pengiriman suatu paket dari lokasi pengirim ke lokasi penerima!"
            button="Lihat"
            link="{{ route('dashboard') }}"
            icon="{{ asset('images/user/3.png') }}"
        />

        <x-service-card
            title="History Pengiriman"
            description="Lihat daftar pengirimanmu kapan saja! Riwayat lengkap & transparan untuk memastikan semuanya terkendali."
            button="Lihat History"
            link="{{ route('user.history') }}"
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
        </div>
    </div>
</div>
<!--Footer-->
<div class="w-full">
    <x-footer :menus="[
        [
            'title' => 'Waktu Kerja',
            'content' => '<p>Senin - Jumat: 08:00 - 18:00</p><p>Sabtu: 09:00 - 15:00</p><p>Minggu: Libur</p>',
        ],
        [
            'title' => 'Layanan Kami',
            'items' => [
                ['label' => 'Live Tracking', 'url' => route('user.live_tracking')],
                ['label' => 'Pengiriman', 'url' => route('user.form_pengiriman')],
                ['label' => 'Cek Tarif', 'url' => route('dashboard')],
                ['label' => 'History Pengiriman', 'url' => route('user.history')],
            ],
        ],
        [
            'title' => 'Privacy & TOS',
            'items' => [
                ['label' => 'Kebijakan Privasi', 'url' => '#'],
                ['label' => 'Syarat & Ketentuan', 'url' => '#'],
            ],
        ],
    ]" />
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 1;
        const totalSlides = 3;

        function moveToNextSlide() {
            currentSlide = currentSlide >= totalSlides ? 1 : currentSlide + 1;
            document.querySelector(`#autoSlide${currentSlide}`).scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'start'
            });
        }

        // Auto slide every 20 seconds (20000 ms)
        setInterval(moveToNextSlide, 20000);
    });

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
                trackingResultDiv.classList.add('hidden'); // Sembunyikan div hasil jika ada error
                trackingErrorMessage.innerText = error.message || 'Terjadi kesalahan saat melacak pengiriman.';
                trackingErrorMessage.classList.remove('hidden');
            });
    }


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

    // Keep the 'Cek Tarif' tab active on page load if a result was just shown
    document.addEventListener('DOMContentLoaded', function () {
        const hasTarif = "{{ session('tarif') || session('error') || $errors->any() ? 'true' : 'false' }}";
        if (hasTarif === "true") {
            selectTab('tarif');
            const tarifResultDiv = document.querySelector('.tarif-result-container');
            if (tarifResultDiv) {
                tarifResultDiv.classList.add('tarif-result-animation');
            }
        } else {
            selectTab('tracking');
        }
    });
</script>
