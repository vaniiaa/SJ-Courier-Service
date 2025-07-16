<x-app-layout>
@section('title', 'Daftar Pengiriman')
        <div class="absolute top-32 left-0 right-0 px-4">
            <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-lg text-center">
             {{-- Membuat form pencarian yang fungsional --}}
                <form action="{{ route('user.daftar_pengiriman') }}" method="GET" class="flex justify-end mb-4 gap-2">
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
                                <td>
                                    @php
                                        $status = strtolower(trim($shipment->currentStatus));
                                        $badgeClass = '';
                                        if ($status === 'menunggu konfirmasi') $badgeClass = 'badge-ghost';
                                        elseif ($status === 'kurir ditugaskan') $badgeClass = 'badge-info';
                                        elseif ($status === 'kurir menuju lokasi penjemputan') $badgeClass = 'badge-warning';
                                        elseif ($status === 'paket telah di-pickup') $badgeClass = 'badge-primary';
                                        elseif ($status === 'dalam perjalanan ke penerima') $badgeClass = 'badge-secondary';
                                        elseif ($status === 'pesanan selesai') $badgeClass = 'badge-success';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $shipment->currentStatus }}</span>
                                </td>
                                <td>
                                    <div class="w-20 p-1 bg-white border">
                                        {!! QrCode::size(70)->generate('https://sj-courier-service-production-3685.up.railway.app/') !!}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('user.confirmation', ['order' => $shipment->orderID]) }}"
                                    class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700">Detail</a>
                                     {{-- Logika untuk menampilkan tombol Batalkan --}}
                                        @php
                                            $cancellableStatuses = ['Menunggu Pembayaran', 'Menunggu Penjemputan', 'Kurir Belum Ditugaskan'];
                                        @endphp
                                        @if (in_array($shipment->currentStatus, $cancellableStatuses))
                                            <form action="{{ route('user.shipment.cancel', $shipment->shipmentID) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pesanan dengan resi {{ $shipment->tracking_number }}?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 bg-red-600 text-white py-1 rounded text-xs hover:bg-red-700 shadow">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <p>Tidak ada pengiriman yang sedang aktif.</p>
                                    <a href="{{ route('user.form_pengiriman') }}" class="btn btn-sm btn-primary mt-4">Buat Pengiriman Baru</a>
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