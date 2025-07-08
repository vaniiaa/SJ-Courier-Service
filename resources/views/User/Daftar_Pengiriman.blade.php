<x-app-layout>
    <div class="relative">
         <div class="bg-[rgba(255,165,0,0.75)] p-6 shadow-md h-40 absolute top-0 left-1/2 transform -translate-x-1/2 z-0" 
             style="width: 100vw; margin-left: -50vw; left: 50%;"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-black mb-8">Daftar Pengiriman</h1>
            <div class="bg-white rounded-lg shadow-md p-6">
             {{-- Membuat form pencarian yang fungsional --}}
                <form action="{{ route('customer.active') }}" method="GET" class="flex justify-end mb-4 gap-2">
                    <input type="text" name="search" placeholder="Cari resi, penerima..." value="{{ request('search') }}" class="input input-bordered w-full max-w-xs">
                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
                </form>
                <div class="overflow-x-auto border border-gray-300 rounded-lg">
    <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
        <thead class="bg-gray-50 text-gray-700 text-sm">
            <tr class="border border-gray-300">
                <th class="px-4 py-2 text-center">No</th>
                <th class="px-4 py-2 text-center">Resi</th>
                <th class="px-4 py-2 text-left">Pengirim</th>
                <th class="px-4 py-2 text-left">Alamat Jemput</th>
                <th class="px-4 py-2 text-left">Penerima</th>
                <th class="px-4 py-2 text-left">Alamat Tujuan</th>
                <th class="px-4 py-2 text-left">Kurir</th>
                <th class="px-4 py-2 text-left">No.Telepon Kurir</th>
                <th class="px-4 py-2 text-center">Status</th>
                <th class="px-4 py-2 text-center">QR Code</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
                        <tbody>
                            @forelse ($shipments as $shipment)
                            <tr class="hover">
                                <th>{{ $loop->iteration + $shipments->firstItem() - 1 }}</th>
                                <td>{{ $shipment->tracking_number }}</td>
                                <td>{{ $shipment->order->sender->name }}</td>
                                <td>{{ Str::limit($shipment->order->pickupAddress, 25) }}</td>
                                <td>{{ $shipment->order->receiverName }}</td>
                                <td>{{ Str::limit($shipment->order->receiverAddress, 25) }}</td>
                                <td>{{ $shipment->courier->name ?? 'Belum Ditentukan' }}</td>
                                <td>{{ $shipment->courier->phone ?? 'Belum Tersedia' }}</td>
                                <td><span class="badge badge-info">{{ $shipment->currentStatus }}</span></td>
                                <td>
                                    <div class="w-20 p-1 bg-white border">
    {!! QrCode::size(70)->generate('https://sj-courier-service-production.up.railway.app/') !!}
</div>

                                </td>
                                <td>
                                    <a href="{{ route('shipments.confirmation', ['order' => $shipment->orderID]) }}"
   class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700">
    Detail
</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <p>Tidak ada pengiriman yang sedang aktif.</p>
                                    <a href="{{ route('shipments.create.step1') }}" class="btn btn-sm btn-primary mt-4">Buat Pengiriman Baru</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 <div class="mt-4">{{ $shipments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>