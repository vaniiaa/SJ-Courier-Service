<x-app-layout>
 
    <div class="max-w-md mx-auto px-4 py-8">
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
                    <p class="font-bold text-lg">{{ $shipment->tracking_number }}</p>
                </div>
            </div>

            {{-- QR Code dan Nomor Resi --}}
<div class="text-center my-4">
    {{-- QR Code berisi URL Google Drive --}}
    @php
        $qrContent = 'https://sj-courier-service-production.up.railway.app/';
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
                    <p>{{ $shipment->order->receiverName }}</p>
                    <p class="font-bold mt-1">Alamat Tujuan:</p>
                    <p>{{ $shipment->order->receiverAddress }}</p>
                </div>
                <div>
                    <p class="font-bold">Pengirim:</p>
                    <p>{{ $shipment->order->sender->name }}</p>
                    {{-- Tampilkan email & no hp --}}
                    <p>{{ $shipment->order->sender->email }}</p>
                    <p>{{ $shipment->order->sender->phone }}</p>
                    <p class="font-bold mt-1">Alamat:</p>
                    <p>{{ $shipment->order->pickupAddress }}</p>
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="grid grid-cols-2 gap-4 border-b py-2 text-xs">
                <div>
                    <p><strong>Berat:</strong> {{ $shipment->weightKG }} Kg</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($shipment->finalPrice, 0, ',', '.') }}</p>
                </div>
                 <div>
                    <p><strong>Pembayaran:</strong> {{ $shipment->order->payments->first()->paymentMethod ?? 'N/A' }}</p>
                </div>
            </div>
             <p class="text-center text-xs mt-2">Terima kasih telah menggunakan layanan kami!</p>
        </div>
<div class="text-center mt-6">
    <a href="{{ route('User.printResi', ['shipmentID' => $shipment->shipmentID]) }}"
       target="_blank"
       class="btn bg-blue-500 text-white">
       Cetak Resi
    </a>


            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Kembali ke Dashboard</a>
        </div>
    </div>
</x-app-layout>
