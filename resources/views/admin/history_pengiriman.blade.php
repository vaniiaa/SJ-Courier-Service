{{-- admin/history_pengiriman.blade.php --}}

@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'History Pengiriman')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* CSS untuk paginasi */
    nav[role="navigation"] > div > span,
    nav[role="navigation"] > div > a {
        margin-right: 8px;
        padding: 6px 12px;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        color: #1f2937;
    }

    nav[role="navigation"] > div > span[aria-current="page"] {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
</style>

<div class="absolute top-36 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('admin.history_pengiriman') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto border border-gray-300 rounded-lg mt-[-10px]">
            <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
                <thead class="bg-gray-50 text-gray-700 text-sm">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-center">Resi</th>
                        <th class="px-4 py-2 text-left">Nama Pengirim</th>
                        <th class="px-4 py-2 text-left">Alamat Penjemputan</th>
                        <th class="px-4 py-2 text-left">Nama Penerima</th>
                        <th class="px-4 py-2 text-left">Alamat Tujuan</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-center">Berat (kg)</th>
                        <th class="px-4 py-2 text-center">Harga (Rp)</th>
                        <th class="px-4 py-2 text-center">Metode Pembayaran</th>
                        <th class="px-4 py-2 text-left">Kurir</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengiriman as $index => $data)
                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">
                                {{ ($pengiriman->currentPage() - 1) * $pengiriman->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $data->tracking_number }}</td>
                            <td class="px-4 py-2">{{ $data->order->sender->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $data->order->pickupAddress ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $data->order->receiverName ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $data->order->receiverAddress ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->created_at ? $data->created_at->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->weightKG }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($data->finalPrice, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->order->payments->first()->paymentMethod ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->courier->name ?? 'Belum Ditentukan' }}</td>
                            <td class="px-4 py-2 text-center font-bold
                                @php $status = strtolower(trim($data->currentStatus)); @endphp
                                @if ($status === 'menunggu konfirmasi') text-gray-500
                                @elseif ($status === 'kurir ditugaskan') text-blue-600
                                @elseif ($status === 'kurir menuju lokasi penjemputan') text-yellow-600
                                @elseif ($status === 'paket telah di-pickup') text-purple-600
                                @elseif ($status === 'dalam perjalanan ke penerima') text-red-600
                                @elseif ($status === 'pesanan selesai') text-green-600
                                @else text-black
                                @endif">
                                {{ $data->currentStatus }}
                            </td>
                           <td class="px-4 py-2 text-center">
    <div class="flex justify-center gap-2">
        <a href="{{ route('admin.downloadResi', ['shipmentID' => $data->shipmentID]) }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-xs shadow-md shadow-gray-700"
           title="Unduh">
            <i class="fas fa-download"></i>
        </a>
        <a href="{{ route('admin.printResi', ['shipmentID' => $data->shipmentID]) }}"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-xs shadow-md shadow-gray-700"
           title="Print">
            <i class="fas fa-print"></i>
        </a>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-4 text-center text-gray-500">Tidak ada data riwayat pengiriman yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if ($pengiriman->hasPages())
            <div class="mt-6 flex justify-end pr-4">
                <nav class="inline-flex -space-x-px text-sm shadow-sm" aria-label="Pagination">
                    @if ($pengiriman->onFirstPage())
                        <span class="px-3 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-gray-400 cursor-default">Sebelumnya</span>
                    @else
                        <a href="{{ $pengiriman->previousPageUrl() }}" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Sebelumnya</a>
                    @endif

                    @foreach ($pengiriman->getUrlRange(1, $pengiriman->lastPage()) as $page => $url)
                        @if ($page == $pengiriman->currentPage())
                            <span class="px-3 py-2 border border-gray-300 bg-yellow-400 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-blue-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($pengiriman->hasMorePages())
                        <a href="{{ $pengiriman->nextPageUrl() }}" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-200">Berikutnya</a>
                    @else
                        <span class="px-3 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-gray-400 cursor-default">Berikutnya</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection
