<footer class="bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black py-8">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Logo dan Sosial Media -->
        <div>
            <img src="{{ asset('images/admin/logo.png') }}" alt="Lion Parcel" class="h-10 mb-4">
            SJ City Courier adalah layanan pengiriman cepat, aman, dan terpercaya. Dengan sistem terintegrasi, kurir dapat memantau, memperbarui status, dan mengonfirmasi pengiriman secara real-time untuk memastikan layanan yang efisien dan transparan. 
        </div>

        <!-- Navigasi Menu -->
        <div>
            <h3 class="font-bold mb-2">Waktu Kerja</h3>
            <p>Senin - Jumat: 08:00 - 18:00</p>
            <p>Sabtu: 09:00 - 15:00</p>
            <p>Minggu: Libur</p>
        </div>

        <!-- Layanan & Bermitra -->
        <div>
            <h3 class="font-bold mb-2">Layanan Kami</h3>
            <ul class="space-y-1">
                <li><a href="#" class="link link-hover">Live Tracking</a></li>
                <li><a href="#" class="link link-hover">Pengiriman</a></li>
                <li><a href="#" class="link link-hover">Cek Tarif</a></li>
                <li><a href="#" class="link link-hover">History Pengiriman</a></li>
            </ul>
        </div>

        <!-- Privbasi & TOS -->
        <div>
            <h3 class="font-bold mb-2">Privacy & TOS</h3>
            <a href="{{ asset('/login')}}" class="hover:underline">Login</a>
        </div>
    </div>
    <div class="text-center text-sm text-black mt-8">
        &copy; 2025 All Rights Reserved - SJ Courier Service
    </div>
</footer>
