{{-- 
    Nama File   : daftar_pengiriman.blade.php
    Deskripsi   : Menampilkan daftar pengiriman untuk kurir, termasuk detail pengiriman melalui modal.
    Dibuat Oleh : [Vania] - [3312301024]
    Tanggal     : 1 Juni 2025
--}}

@extends('layouts.kurir_page') 

@section('title', 'Daftar Pengiriman')

@section('content')

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="absolute top-32 left-0 right-0 px-4" x-data="{ showModal: false, selectedData: {} }" @keydown.escape.window="showModal = false">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4 mb-20">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('kurir.daftar_pengiriman') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

        {{-- Tabel Daftar Pengiriman --}}
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
                        <th class="px-4 py-2 text-left">Tanggal Pemesanan</th>
                        <th class="px-4 py-2 text-left">Berat (Kg)</th>
                        <th class="px-4 py-2 text-center">Harga (Rp)</th>
                        <th class="px-4 py-2 text-center">Kurir</th>
                        <th class="px-4 py-2 text-center">Tanggal Pengiriman</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ganti variabel $pengiriman menjadi $shipments dan $data menjadi $shipment --}}
                    @forelse ($shipments as $shipment)
                        <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">{{ $shipments->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->tracking_number }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->sender->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ Str::limit($shipment->order->pickupAddress, 25) }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverName }}</td>
                            <td class="px-4 py-2">{{ Str::limit($shipment->order->receiverAddress, 25) }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->orderDate ? $shipment->order->orderDate->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->weightKG }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($shipment->finalPrice, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->courier->name ?? 'Belum Ditentukan' }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->pickupTimestamp ? \Carbon\Carbon::parse($shipment->pickupTimestamp)->format('Y-m-d') : 'Belum Ditentukan' }}</td>
                            <td class="px-4 py-2 text-center font-semibold text-sm
                                @php $status = strtolower(trim($shipment->currentStatus)); @endphp
                                @if ($status === 'menunggu konfirmasi') text-gray-500
                                @elseif ($status === 'kurir ditugaskan') text-blue-600
                                @elseif ($status === 'kurir menuju lokasi penjemputan') text-yellow-600
                                @elseif ($status === 'paket telah di-pickup') text-purple-600
                                @elseif ($status === 'dalam perjalanan ke penerima') text-red-600
                                @elseif ($status === 'pesanan selesai') text-green-600
                                @else text-black @endif">
                                {{ $shipment->currentStatus }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700"
                                        @click="showModal = true; selectedData = {
                                            resi: '{{ $shipment->tracking_number }}',
                                            pengirim: '{{ $shipment->order->sender->name ?? 'N/A' }}',
                                            alamat_jemput: '{{ $shipment->order->pickupAddress }}',
                                            penerima: '{{ $shipment->order->receiverName }}',
                                            alamat_tujuan: '{{ $shipment->order->receiverAddress }}',
                                            tanggal: '{{ $shipment->order->orderDate ? $shipment->order->orderDate->format('Y-m-d') : '-' }}',
                                            berat: '{{ $shipment->weightKG }}',
                                            harga: '{{ number_format($shipment->finalPrice, 0, ',', '.') }}',
                                            kurir: '{{ $shipment->courier->name ?? 'Belum Ditentukan' }}',
                                            status: '{{ ucfirst($shipment->currentStatus) }}',
                                            catatan: '{{ $shipment->noteadmin ?? '' }}',
                                            tanggal_pengiriman: '{{ $shipment->pickupTimestamp ? \Carbon\Carbon::parse($shipment->pickupTimestamp)->format('Y-m-d') : 'Belum Ditentukan' }}',
                                            bukti: '{{ $shipment->delivery_proof ? asset('storage/' . $shipment->delivery_proof) : '' }}'
                                        }">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-4 text-center text-gray-500">Tidak ada data pengiriman untuk Anda.</td>
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

    {{-- Modal Detail Pengiriman --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded-lg shadow-md shadow-gray-700 w-[1000px] max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center">
                <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-full">
                <h5 class="text-xl font-semibold flex-1 ml-4">Detail Pengiriman</h5>
                <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
            </div>
            <hr class="my-4 border-gray-300">

            <form class="text-sm space-y-2">
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Resi</label>
                    <input type="text" :value="selectedData.resi" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Pengirim</label>
                    <input type="text" :value="selectedData.pengirim" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Penjemputan</label>
                    <textarea rows="2" :value="selectedData.alamat_jemput" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Penerima</label>
                    <input type="text" :value="selectedData.penerima" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Tujuan</label>
                    <textarea rows="2" :value="selectedData.alamat_tujuan" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Tanggal Pemesanan</label>
                    <input type="text"
                        :value="selectedData.tanggal && selectedData.tanggal !== '0000-00-00 00:00:00'
                            ? new Date(selectedData.tanggal).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })
                            : 'Tidak Tersedia'"
                        class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Berat (kg)</label>
                    <input type="text" :value="selectedData.berat" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Harga (Rp)</label>
                    <input type="text" :value="selectedData.harga" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Kurir</label>
                    <input type="text" :value="selectedData.kurir" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Tanggal Pengiriman</label>
                    <input type="text" :value="selectedData.tanggal_pengiriman" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Status</label>
                    <input type="text" :value="selectedData.status" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 text-left font-medium text-gray-700 after:content-[':']">Catatan</label>
                    <textarea rows="2" :value="selectedData.catatan" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
                </div>

                <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Bukti Pengiriman</label>
                        <div class="flex-1">
                            <template x-if="selectedData.bukti">
                                <img :src="selectedData.bukti" alt="Bukti Pengiriman" class="w-32 rounded-md">
                            </template>
                            <template x-if="!selectedData.bukti">
                                <p class="text-sm text-gray-500 italic">Bukti belum tersedia</p>
                            </template>
                        </div>
                    </div>
            </form>
            <div class="flex justify-end mt-6">
                <button @click="showModal = false" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow">
                    Tutup
                </button>
            </div>
        </div>   
    </div>
</div>

@endsection