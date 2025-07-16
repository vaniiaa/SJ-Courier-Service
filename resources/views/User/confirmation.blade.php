<x-app-layout>
    @section('title', 'Konfirmasi Pembayaran')
    <div class="max-w-md mx-auto px-4 py-8" id="confirmation-container">
        <h1 class="text-2xl font-bold text-center mb-4">Pengiriman Berhasil Dibuat!</h1>
        
        {{-- Kontainer Label Pengiriman --}}
        <div class="bg-white rounded-lg shadow-lg p-4 border border-gray-300">
            {{-- Header Label --}}
            <div class="flex justify-between items-center border-b pb-2 mb-2">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-8 w-8 object-contain" />
                    <span class="font-bold">SJ Courier Service</span>
                </div>
                <div class="text-right">
                    <p class="text-xs">No. Resi:</p>
                    {{-- Handle jika shipment belum dibuat (pembayaran online pending) --}}
                    <p class="font-bold text-lg">{{ $shipment->tracking_number ?? 'Akan dibuat' }}</p>
                </div>
            </div>

            {{-- QR Code dan Nomor Resi --}}
            <div class="text-center my-4">
            {{-- QR Code berisi URL Google Drive --}}
                @php
                 $qrContent = 'https://sj-courier-service-production-3685.up.railway.app/';
                @endphp
            <div class="inline-block p-2 border">
             {!! QrCode::size(120)->generate($qrContent) !!}
            </div>
            <p class="font-mono tracking-widest mt-2">{{ $shipment->tracking_number }}</p>
            </div>
            
            {{-- Detail Pengirim & Penerima --}}
            <div class="grid grid-cols-2 gap-4 border-t border-b py-2 text-xs">
                <div>
                    <p class="font-bold">Penerima:</p>
                    <p>{{ $order->receiverName }}</p>
                    <p class="font-bold mt-1">Alamat Tujuan:</p>
                    <p>{{ $order->receiverAddress }}</p>
                </div>
                <div>
                    <p class="font-bold">Pengirim:</p>
                    <p>{{ $order->sender->name }}</p>
                    {{-- Tampilkan email & no hp --}}
                    <p>{{ $order->sender->email }}</p>
                    <p>{{ $order->sender->phone }}</p>
                    <p class="font-bold mt-1">Alamat:</p>
                    <p>{{ $order->pickupAddress }}</p>
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="grid grid-cols-2 gap-4 border-b py-2 text-xs">
                <div>
                    <p><strong>Harga:</strong> Rp {{ number_format($order->estimatedPrice, 0, ',', '.') }}</p>
                </div>
                 <div>
                    {{-- Tampilkan metode pembayaran dari tabel payment jika ada, jika tidak, tentukan berdasarkan shipment --}}
                    <p><strong>Pembayaran:</strong> {{ $order->payments->first()->paymentMethod ?? ($shipment ? 'COD' : 'Online') }}</p>
                </div>
            </div>
             <p class="text-center text-xs mt-2">Terima kasih telah menggunakan layanan kami!</p>
        </div>

        {{-- Tampilkan pesan info jika ada (misal: fallback ke COD) --}}
        @if (session('info'))
            <div role="alert" class="alert alert-info mt-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Tombol dan Status Pembayaran --}}
        <div class="text-center mt-6">
            @if (session('snap_token'))
                <button id="pay-button" class="btn btn-primary">Lanjutkan Pembayaran</button>
            @else
                {{-- Jika ini pesanan COD atau fallback, tampilkan tombol cetak --}}
                 <a href="{{ route('User.printResi', ['shipmentID' => $shipment->shipmentID]) }}"
                    target="_blank"
                    class="btn bg-blue-500 text-white">
                    Cetak Resi
                </a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Kembali ke Dashboard</a>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            // Ambil snap_token dari session yang di-pass oleh controller
            const snapToken = @json(session('snap_token'));

            if (payButton && snapToken) {
                payButton.addEventListener('click', function () {
                    // Tampilkan jendela pembayaran Midtrans
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            // Redirect ke halaman history setelah pembayaran sukses
                            window.location.href = "{{ route('user.history') }}?payment=success";
                        },
                        onPending: function(result) {
                            // Anda bisa menambahkan notifikasi di sini jika perlu
                            console.log('Menunggu pembayaran:', result);
                            alert("Menunggu pembayaran Anda. Silakan selesaikan di jendela yang terbuka.");
                        },
                        onError: function(result) {
                            // Anda bisa menambahkan notifikasi error di sini
                            console.error('Pembayaran gagal:', result);
                            alert("Pembayaran Gagal. Silakan coba lagi.");
                        },
                        onClose: function() {
                            // Pengguna menutup jendela pembayaran sebelum selesai
                            console.log('Jendela pembayaran ditutup.');
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
