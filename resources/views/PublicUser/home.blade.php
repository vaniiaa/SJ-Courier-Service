<!-- resources/views/home.blade.php -->
@extends('layouts.PublicUser')

@section('title', 'Home')

@section('content')
<div class="w-full  mx-auto">
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
        setInterval(moveToNextSlide, 20000);
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
      <input type="text" placeholder="Asal" class="input input-bordered" disabled />
      <input type="text" placeholder="Tujuan" class="input input-bordered" disabled />
      <select class="select select-bordered" disabled>
        <option>Kecil 1-5 Kg</option>
      </select>
      <button class="btn bg-gradient-to-r from-yellow-400 to-yellow-300 text-black shadow font-semibold" disabled>
        Cek Tarif
      </button>
    </div>
    <p class="text-red-500 text-sm mt-2">Fitur hanya tersedia setelah login</p>
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
                <img src="{{ asset('images/user/welcome.jpg') }}" alt="Delivery Person" class="max-w-sm">
            </div>
        </div>
    </div>
    
    <!-- Services Section -->
    <div id="services" class="bg-gray-100 py-8 md:py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-xl md:text-2xl font-bold text-center mb-6 md:mb-8">Layanan</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Live Tracking -->
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                    <figure class="px-4 md:px-6 pt-4 md:pt-6">
                        <!-- <div class=" rounded-full p-3 md:p-4 w-16 h-16 md:w-24 md:h-24 flex items-center justify-center mx-auto"> -->
                            <img src="{{ asset('images/user/layanan2.png') }}" alt="Live Tracking" class="w-12 h-12 object-contain object-center mx-auto">
                        <!-- </div> -->
                    </figure>
                    <div class="card-body text-center p-4 md:p-6">
                        <h3 class="card-title justify-center text-base md:text-lg">Live Tracking</h3>
                        <p class="text-xs md:text-sm">Tap untuk lacak paket pengiriman, melihat pergerakan secara real-time dan pastikan titik tepat waktu!</p>
                        <div class="card-actions justify-center mt-3 md:mt-4">
                            <a href="{{ route('login') }}" class="btn btn-outline btn-warning btn-xs md:btn-sm">Lacak Sekarang</a>
                        </div>
                    </div>
                </div>
                
                <!-- Request Delivery -->
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                    <figure class="px-4 md:px-6 pt-4 md:pt-6">
                        <!-- <div class="bg-yellow-100 rounded-full p-3 md:p-4 w-16 h-16 md:w-24 md:h-24 flex items-center justify-center mx-auto"> -->
                            <img src="{{ asset('images/user/layanan1.png') }}" alt="Request Delivery" class="w-12 h-12 object-contain object-center mx-auto">
                        <!-- </div> -->
                    </figure>
                    <div class="card-body text-center p-4 md:p-6">
                        <h3 class="card-title justify-center text-base md:text-lg">Permintaan Pengiriman</h3>
                        <p class="text-xs md:text-sm">Buat/ubah permintaan pengiriman cepat dan detail sertakan pembayaran dengan mudah!</p>
                        <div class="card-actions justify-center mt-3 md:mt-4">
                            <a href="{{ route('login') }}" class="btn btn-outline btn-warning btn-xs md:btn-sm">Buat Pengiriman</a>
                        </div>
                    </div>
                </div>
                
                <!-- Delivery List -->
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                    <figure class="px-4 md:px-6 pt-4 md:pt-6">
                        <!-- <div class="bg-yellow-100 rounded-full p-3 md:p-4 w-16 h-16 md:w-24 md:h-24 flex items-center justify-center mx-auto"> -->
                            <img src="{{ asset('images/user/layanan3.png') }}" alt="Delivery List" class="w-12 h-12 object-contain object-center mx-auto">
                        <!-- </div> -->
                    </figure>
                    <div class="card-body text-center p-4 md:p-6">
                        <h3 class="card-title justify-center text-base md:text-lg">Daftar Pengiriman</h3>
                        <p class="text-xs md:text-sm">Lihat dan cari Daftar Pengiriman Anda Sekarang!</p>
                        <div class="card-actions justify-center mt-3 md:mt-4">
                            <a href="{{ route('login') }}" class="btn btn-outline btn-warning btn-xs md:btn-sm">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- History -->
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                    <figure class="px-4 md:px-6 pt-4 md:pt-6">
                        <!-- <div class="bg-yellow-100 rounded-full p-3 md:p-4 w-16 h-16 md:w-24 md:h-24 flex items-center justify-center mx-auto"> -->
                            <img src="{{ asset('images/user/layanan4.png') }}" alt="History" class="w-12 h-12 object-contain object-center mx-auto">
                        <!-- </div> -->
                    </figure>
                    <div class="card-body text-center p-4 md:p-6">
                        <h3 class="card-title justify-center text-base md:text-lg">History Pengiriman</h3>
                        <p class="text-xs md:text-sm">Lihat status pengiriman lewat dan saat ini, kemudahan memiliki semuanya terekam!</p>
                        <div class="card-actions justify-center mt-3 md:mt-4">
                            <a href="{{ route('login') }}" class="btn btn-outline btn-warning btn-xs md:btn-sm">Lihat History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tips Section -->
    <div id="tips" class="container mx-auto px-4 py-8 md:py-12">
        <h2 class="text-xl md:text-2xl font-bold text-center mb-6 md:mb-8">Tips & Panduan Pengiriman</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
            <div class="hidden md:block">
                <img src="{{ asset('images/user/tips.png') }}" alt="Packing Tips" class="max-w-full">
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
    
    <!-- CTA Section -->
    <div class="bg-courier-secondary py-8 md:py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-xl md:text-2xl font-bold text-white mb-3 md:mb-4">Siap mengirim paket?</h2>
            <p class="text-sm md:text-base text-white mb-6">Daftar sekarang dan nikmati kemudahan pengiriman barang dengan SJ Courier Service!</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}" class="btn bg-white hover:bg-gray-200 text-courier-secondary border-none btn-sm md:btn-md">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="btn btn-outline text-white hover:bg-white hover:text-courier-secondary btn-sm md:btn-md">Login</a>
            </div>
        </div>
    </div>
@endsection