<footer class="bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black py-6 px-10 mt-10">
    <div class="grid grid-cols-4 gap-4">
        <div>
        <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-12 w-12 object-contain" />
            <p>SJ City Courier adalah layanan pengiriman cepat, aman, dan terpercaya.</p>
        </div>
        <div>
            <h3 class="font-bold">Waktu Kerja</h3>
            <p>Senin - Jumat: 08:00 - 18:00</p>
            <p>Sabtu: 09:00 - 15:00</p>
            <p>Minggu: Libur</p>
        </div>
        <div>
            <h3 class="font-bold">Navigasi</h3>
            <ul>
                <li><a href="{{ asset('kurir/daftar_pengiriman')}}" class="hover:underline">Daftar Pengiriman</a></li>
                <li><a href="{{ asset('kurir/live_tracking')}}" class="hover:underline">Live Tracking</a></li>
                <li><a href="{{ asset('kurir/kelola_status')}}" class="hover:underline">Kelola Status</a></li>
                <li><a href="{{ asset('kurir/kelola_status')}}" class="hover:underline">Konfirmasi Pengiriman</a></li>
            </ul>
        </div>
    </div>
</footer>
