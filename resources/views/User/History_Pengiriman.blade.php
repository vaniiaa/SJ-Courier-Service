<x-app-layout>
    <div class="relative">
        <div class="bg-yellow-400 p-6 shadow-md h-40 w-full absolute top-0 left-0 z-0"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-black mb-8">History Pengiriman</h1>
            <div class="bg-white rounded-lg shadow-md p-6">
                {{-- Membuat form pencarian yang fungsional --}}
                <form action="{{ route('customer.history') }}" method="GET" class="flex justify-end mb-4 gap-2">
                    <input type="text" name="search" placeholder="Cari resi, penerima..." value="{{ request('search') }}" class="input input-bordered w-full max-w-xs">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Resi</th>
                                <th>Pengirim</th>
                                <th>Penerima</th>
                                <th>Kurir</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse ($shipments as $shipment)
                            <tr class="hover">
                                <th>{{ $loop->iteration + $shipments->firstItem() - 1 }}</th>
                                <td>{{ $shipment->tracking_number }}</td>
                                <td>{{ $shipment->order->sender->name }}</td>
                                <td>{{ $shipment->order->receiverName }}</td>
                                <td>{{ $shipment->courier->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->updated_at->format('d M Y') }}</td>
                                <td><span class="badge badge-success text-white">{{ $shipment->currentStatus }}</span></td>
                                <td class="flex gap-2">
                                    {{-- Tombol untuk melihat detail/cetak label --}}
                                    <a href="{{ route('shipments.confirmation', ['order' => $shipment->orderID]) }}" target="_blank" class="btn btn-xs btn-outline btn-info" title="Lihat Detail/Cetak">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </a>
                                    {{-- Tombol untuk mengunduh bukti pengiriman, hanya muncul jika file ada --}}
                                    @if($shipment->delivery_proof)
                                    <a href="{{ asset('storage/' . $shipment->delivery_proof) }}" download="bukti-{{ $shipment->tracking_number }}.jpg" class="btn btn-xs btn-outline btn-primary" title="Unduh Bukti Pengiriman">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-8">Belum ada riwayat pengiriman yang selesai.</td>
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