@extends('layouts.kurir')

@section('content')
<div class="autoplay w-screen h-auto relative overflow-hidden">
    <div>
        <img src="{{ asset('images/kurir/carousel1.jpg') }}" class="w-full h-full object-cover" />
    </div>
    <div>
        <img src="{{ asset('images/kurir/carousel3.png') }}" class="w-full h-full object-cover" />
    </div>
    <div>
        <img src="{{ asset('images/kurir/carousel2.jpg') }}" class="w-full h-full object-cover" />
    </div>
</div>

<!-- Text and Image Section Below Carousel -->
<div class="flex mt-10">
    <!-- Text Block -->
    <div class="flex-1 px-10">
        <h2 class="text-2xl font-bold">Selamat Datang di Dashboard Kurir SJ Courier Service!</h2>
        <p class="mt-4">Dashboard ini dirancang untuk membantu kurir dalam mengelola dan memantau pengiriman dengan lebih efisien. Melalui dashboard ini, kurir dapat melihat daftar paket yang harus dikirim, mengakses fitur Live Tracking, memperbarui status pengiriman secara real-time, serta mengonfirmasi dan mengupload bukti paket yang telah berhasil diantar. Dengan sistem yang terintegrasi ini, proses pengiriman menjadi lebih cepat, akurat, dan transparan bagi pelanggan.</p>
    </div>

    <!-- Image Block (Right Side) -->
    <div class="w-1/3 px-5">
        <img src="{{ asset('images/kurir/welcome.jpg') }}" alt="Image" class="w-full rounded-lg">
    </div>
</div>

<!-- Judul Layanan dengan Garis Bawah Gradien -->
<div class="text-center mt-16">
    <h2 class="text-3xl font-bold inline-block relative pb-3">
        Layanan Kami
        <span class="absolute left-0 bottom-0 w-full h-1 bg-gradient-to-r from-[#FFA500] to-[#FFD45B] rounded-full"></span>
    </h2>
</div>

<!-- Card Section Below Image Block -->
<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-10">
    <!-- Card 1 -->
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset('images/kurir/layanan1.jpg') }}" alt="Layanan 1" class="w-full h-40 object-cover">
        <div class="p-5 pb-4 flex flex-col items-center text-center">
            <h3 class="text-xl font-semibold">Daftar Pengiriman</h3>
            <p class="mt-2">Lihat daftar pengiriman Anda dan selesaikan tugas pengiriman dengan efisien!</p>
            <a href="{{ asset('kurir/daftar_pengiriman')}}" class="mt-4 inline-block bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black px-4 py-2 rounded-lg hover:bg-gradient-to-r from-[#FFA500] to-[#FFD45B] transition">Lihat Daftar Pengiriman</a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset('images/kurir/layanan2.jpg') }}" alt="Layanan 2" class="w-full h-40 object-cover">
        <div class="p-5 pb-4 flex flex-col items-center text-center">
            <h3 class="text-xl font-semibold">Live Tracking</h3>
            <p class="mt-2">Aktifkan Pelacakan real-time untuk memastikan paket sampai tepat waktu!</p>
            <a href="{{ asset('kurir/live_tracking')}}" class="mt-4 inline-block bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black px-4 py-2 rounded-lg hover:opacity-90 transition">Aktifkan Live Tracking</a>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset('images/kurir/layanan3.jpg') }}" alt="Layanan 3" class="w-full h-40 object-cover">
        <div class="p-5 pb-4 flex flex-col items-center text-center">
            <h3 class="text-xl font-semibold">Kelola Status</h3>
            <p class="mt-2">Perbarui status pengiriman dengan cepat dan mudah, langsung dari dashboard!</p>
            <a href="{{ asset('kurir/kelola_status')}}" class="mt-4 inline-block bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black px-4 py-2 rounded-lg hover:opacity-90 transition">Update Status</a>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset('images/kurir/layanan4.jpg') }}" alt="Layanan 4" class="w-full h-40 object-cover">
        <div class="p-5 pb-4 flex flex-col items-center text-center">
            <h3 class="text-xl font-semibold">Konfirmasi Pengiriman</h3>
            <p class="mt-2">Pastikan paket sudah diterima dengan mengupload bukti pengiriman Anda!</p>
            <a href="{{ asset('kurir/kelola_status')}}" class="mt-4 inline-block bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black px-4 py-2 rounded-lg hover:bg-gradient-to-r from-[#FFA500] to-[#FFD45B] transition">Konfirmasi</a>
        </div>
    </div>
</div>

<!-- Text and Image Section Below Carousel -->
<div class="flex items-center mt-5">
    <!-- Image Block (Left Side) -->
    <div class="w-1/2 px-5">
        <img src="{{ asset('images/kurir/tips.png') }}" alt="Image" class="w-full rounded-lg">
    </div>

    <!-- Tips & Panduan Pengiriman -->
    <div class="flex justify-between items-start mt-0">
        <!-- Bagian Tips -->
        <div class="flex-1">
            <h2 class="text-xl font-bold mb-6">Tips & Panduan Pengiriman</h2>
            <div class="space-y-5">
                <!-- Tip 1 -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h18v18H3z"/><path d="M7 8h10M7 12h6M7 16h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Pastikan Paket Tertutup Rapat</p>
                        <p class="text-gray-600 text-sm">Gunakan lakban kuat dan pastikan tidak ada celah yang terbuka.</p>
                    </div>
                </div>

                <!-- Tip 2 -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10h18M3 14h18M5 6h14M5 18h14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Jaga Waktu Pengiriman</p>
                        <p class="text-gray-600 text-sm">Kirim paket sesuai jadwal untuk menjaga kepuasan pelanggan.</p>
                    </div>
                </div>

                <!-- Tip 3 -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Periksa Kembali Label Pengiriman</p>
                        <p class="text-gray-600 text-sm">Pastikan nama, alamat, dan nomor resi sesuai sebelum dikirim.</p>
                    </div>
                </div>

                <!-- Tip 4 -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M15 8h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Ambil Foto Bukti Pengiriman</p>
                        <p class="text-gray-600 text-sm">Dokumentasikan paket sebelum dan sesudah pengantaran sebagai bukti.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/dasbor.js') }}"></script>
@endsection