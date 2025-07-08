<x-app-layout>
    <div class="relative">
         <div class="bg-[rgba(255,165,0,0.75)] p-6 shadow-md h-40 absolute top-0 left-1/2 transform -translate-x-1/2 z-0" 
             style="width: 100vw; margin-left: -50vw; left: 50%;"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-black mb-8 text-center">Ringkasan & Pembayaran</h1>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6 space-y-4">
                {{-- Ringkasan Pesanan --}}
                <div>
                    <h2 class="text-lg font-semibold mb-2">Ringkasan Pesanan</h2>
                    <div class="text-sm space-y-1">
                        <p><strong>Dari:</strong> {{ $data['pickupAddress'] }}</p>
                        <p><strong>Kepada:</strong> {{ $data['receiverName'] }} ({{ $data['receiverPhoneNumber'] }})</p>
                        <p><strong>Alamat Tujuan:</strong> {{ $data['receiverAddress'] }}</p>
                        <p><strong>Barang:</strong> {{ $data['itemType'] }} ({{ $data['weightKG'] }} Kg)</p>
                        <p><strong>Jarak:</strong> ~{{ number_format($data['estimatedDistanceKM'], 1) }} Km</p>
                    </div>
                </div>
                <div class="divider"></div>
                {{-- Total Biaya --}}
                <div class="text-center">
                    <p class="text-sm">Total Estimasi Biaya</p>
                    <p class="text-3xl font-bold text-primary">Rp {{ number_format($data['estimatedPrice'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                 <h2 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h2>
                <form method="POST" action="{{ route('shipments.store.final') }}">
                    @csrf
                    <div class="space-y-2">
                        <label class="label cursor-pointer p-4 border rounded-lg hover:bg-gray-50">
                            <span class="label-text font-medium">Bayar di Tempat (COD)</span> 
                            <input type="radio" name="paymentMethodOption" value="cod" class="radio radio-primary" checked />
                        </label>
                        <label class="label cursor-pointer p-4 border rounded-lg hover:bg-gray-50">
                            <span class="label-text font-medium">Bayar Online (Virtual Account, E-Wallet, dll)</span> 
                            <input type="radio" name="paymentMethodOption" value="online" class="radio radio-primary" />
                        </label>
                    </div>
                    <div class="text-center mt-8">
                        <button type="submit" class="btn bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-lg shadow-md">Konfirmasi & Buat Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
