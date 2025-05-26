<!-- resources/views/home.blade.php -->
@extends('layouts.PublicUser')

@section('title', 'Home')

@section('content')
<div class="w-full  mx-auto relative">
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
        
        // Auto slide every 20 seconds
        setInterval(moveToNextSlide, 200000);
    });

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
</script>


    
    <!-- Tracking and Tariff Tabs -->
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

<!-- Konten Tab -->
<div class="max-w-xl mx-auto mt-4 border border-black rounded-md shadow bg-white p-6">

  <!-- Tab Live Tracking -->
  <div id="tab-tracking-content">
    <h3 class="font-bold mb-2">Lacak Pengiriman</h3>
    <div class="flex gap-2 flex-col md:flex-row">
      <input type="text" placeholder="111LU67UTW"
             class="input input-bordered w-full" />
      <button class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold">
        Lacak
      </button>
    </div>
  </div>

  <!-- Tab Cek Tarif -->
  <div id="tab-tarif-content" class="hidden">
    <h3 class="font-bold mb-2">Cek Tarif</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
      <input type="text" placeholder="Asal" class="input input-bordered"  />
      <input type="text" placeholder="Tujuan" class="input input-bordered"  />
      <select class="select select-bordered" >
        <option> 1-5 Kg</option>
        <option> 6-10 Kg</option>
      </select>
      <button class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold" >
        Cek Tarif
      </button>
    </div>
  </div>
</div>

    
    <!-- Welcome Section -->
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
    
    <!-- Services Section -->
    <div class="py-10 bg-gray-100">
    <h2 class="text-2xl font-bold text-center mb-6 underline">Layanan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 md:px-12">
        <x-service-card 
            title="Live Tracking"
            description="Tak perlu khawatir soal pengiriman. Pantau posisi paket secara real-time dan pastikan tiba tepat waktu!"
            button="Lacak Sekarang"
            link="{{ asset('kurir/live_tracking') }}"
            icon="{{ asset('images/user/1.png') }}"
        />

        <x-service-card 
            title="Permintaan Pengiriman dan Pembayaran"
            description="Ajukan permintaan pengiriman dan lakukan pembayaran dengan mudah, cepat, dan aman dalam hitungan detik!"
            button="Buat Pengiriman"
            link="{{ asset('kurir/kelola_status') }}"
            icon="{{ asset('images/user/2.png') }}"
        />

        <x-service-card 
            title="Cek Tarif"
            description="Lakukan cek tarif untuk menghitung estimasi biaya pengiriman suatu paket dari lokasi pengirim ke lokasi penerima!"
            button="Lihat"
            link="{{ asset('kurir/kelola_status') }}"
            icon="{{ asset('images/user/3.png') }}"
        />

        <x-service-card 
            title="History Pengiriman"
            description="Lihat daftar pengirimanmu kapan saja! Riwayat lengkap & transparan untuk memastikan semuanya terkendali."
            button="Lihat History"
            link="{{ asset('kurir/history') }}"
            icon="{{ asset('images/user/4.png') }}"
        />
    </div>
</div>


    
    <!-- Tips Section -->
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