<x-app-layout>
    <div class="relative">
        <div class="bg-yellow-400 p-6 shadow-md h-40 w-full absolute top-0 left-0 z-0"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-black mb-8">Daftar Pengiriman</h1>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-end mb-4">
                    <input type="text" placeholder="Search..." class="input input-bordered w-full max-w-xs">
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Resi</th>
                                <th>Pengirim</th>
                                <th>Alamat Jemput</th>
                                <th>Penerima</th>
                                <th>Alamat Tujuan</th>
                                <th>Status</th>
                                <th>QR Code</th>
                                <th>Aksi</th>
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
                                <td><span class="badge badge-info">{{ $shipment->currentStatus }}</span></td>
                                <td>
                                    <div class="w-20 p-1 bg-white border">
                                        {!! QrCode::size(70)->generate($shipment->tracking_number) !!}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('shipments.confirmation', ['order' => $shipment->orderID]) }}" class="btn btn-sm btn-primary">Detail</a>
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