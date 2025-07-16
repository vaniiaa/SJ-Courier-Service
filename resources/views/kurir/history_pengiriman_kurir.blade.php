{{-- 
    Nama File   : history_pengiriman_kurir.blade.php
    Deskripsi   : Menampilkan daftar history pengiriman untuk kurir, termasuk detail pengiriman melalui modal.
    Dibuat Oleh : [Vania] - [3312301024]
    Tanggal     : 1 Juni 2025
--}}

@extends('layouts.kurir_page')

@section('title', 'History Pengiriman')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="absolute top-32 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg mb-20 p-4">
        
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('kurir.history_pengiriman_kurir') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto border border-gray-300 rounded-lg">
            <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
                <thead class="bg-gray-50 text-gray-700 text-sm">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-center">Resi</th>
                        <th class="px-4 py-2 text-left">Nama Pengirim</th>
                        <th class="px-4 py-2 text-left">Alamat Penjemputan</th>
                        <th class="px-4 py-2 text-left">Nama Penerima</th> 
                        <th class="px-4 py-2 text-left">Alamat Tujuan</th>
                        <th class="px-4 py-2 text-left">Kurir</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-center">Berat (kg)</th>
                        <th class="px-4 py-2 text-center">Harga (Rp)</th>
                        <th class="px-4 py-2 text-center">Metode Pembayaran</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Bukti Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $shipment)
                        <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">{{ $loop->iteration + $shipments->firstItem() - 1 }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->tracking_number }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->sender->name }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->pickupAddress }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverName }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverAddress }}</td>
                            <td class="px-4 py-2">{{ $shipment->courier->name ?? N/A }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($shipment->order->orderDate)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->weightKG }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($shipment->finalPrice, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->order->payments->first()->paymentMethod ?? '-' }}</td>
                            <td class="px-4 py-2 text-center font-semibold
                                @php $status = strtolower(trim($shipment->currentStatus)); @endphp
                                @if ($status === 'Pesanan selesai') text-green-600
                                @elseif ($status === 'dibatalkan' || $status === 'dikembalikan') text-red-600
                                @else text-gray-700
                                @endif">
                                {{ $shipment->currentStatus }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if ($shipment->delivery_proof)
                                    <a href="{{ asset('storage/' . $shipment->delivery_proof) }}" target="_blank" class="text-blue-500 hover:underline">Lihat Gambar</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('kurir.downloadResi', ['shipmentID' => $shipment->shipmentID]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs" title="Unduh">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('kurir.printResi', ['shipmentID' => $shipment->shipmentID]) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs" title="Cetak">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center py-4 text-gray-500">Belum ada pengiriman yang selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if ($shipments->hasPages())
            <div class="mt-6 flex justify-end pr-4">
                <nav class="inline-flex -space-x-px text-sm shadow-sm" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($shipments->onFirstPage())
                        <span class="px-3 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-gray-400 cursor-default">Sebelumnya</span>
                    @else
                        <a href="{{ $shipments->previousPageUrl() }}" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Sebelumnya</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($shipments->getUrlRange(1, $shipments->lastPage()) as $page => $url)
                        @if ($page == $shipments->currentPage())
                            <span class="px-3 py-2 border border-gray-300 bg-yellow-400 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-yellow-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($shipments->hasMorePages())
                        <a href="{{ $shipments->nextPageUrl() }}" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Berikutnya</a>
                    @else
                        <span class="px-3 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-gray-400 cursor-default">Berikutnya</span>
                    @endif
                </nav>
            </div>
        @endif

    </div>
</div>
@endsection
