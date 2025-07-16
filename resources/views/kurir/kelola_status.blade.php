{{-- 
    Nama File   : kelola_status.blade.php
    Deskripsi   : Menampilkan daftar kelola status, modal konfirmasi status dan detail status pengiriman
    Dibuat Oleh : [Vania] - [3312301024]
    Tanggal     : 1 Juni 2025
--}}

@extends('layouts.kurir_page') 

@section('title', 'Kelola Status')

@section('content')
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

    [x-cloak] { display: none !important; }
</style>

<div class="absolute top-32 left-0 right-0 px-4" <div class="absolute top-32 left-0 right-0 px-4" 
    x-data="{ 
        showModal: false, 
        showStatus: false, 
        selectedData: {}, 
        nextStatus: '',
        transitions: {
            'menunggu konfirmasi': [{ value: 'kurir menuju lokasi penjemputan', text: 'Mulai Menuju Lokasi Penjemputan' }],
            'kurir ditugaskan': [{ value: 'kurir menuju lokasi penjemputan', text: 'Mulai Menuju Lokasi Penjemputan' }],
            'kurir menuju lokasi penjemputan': [{ value: 'paket telah di-pickup', text: 'Konfirmasi Paket Telah Di-pickup' }],
            'paket telah di-pickup': [{ value: 'dalam perjalanan ke penerima', text: 'Mulai Antar ke Penerima' }],
            'dalam perjalanan ke penerima': [{ value: 'pesanan selesai', text: 'Konfirmasi Pesanan Selesai' }]
        },
        get availableOptions() {
            if (!this.selectedData.status) return [];
            const currentStatusKey = this.selectedData.status.toLowerCase().trim();
            return this.transitions[currentStatusKey] || [];
        }
    }" 
    @keydown.escape.window="showModal = false; showStatus = false">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg mb-20 p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            {{-- Arahkan action form ke route kelola_status --}}
            <form action="{{ route('kurir.kelola_status') }}" method="GET" class="flex items-center gap-2">
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
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-center">Berat (Kg)</th>
                        <th class="px-4 py-2 text-center">Harga (Rp)</th>
                        <th class="px-4 py-2 text-center">Kurir</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Bukti Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $index => $shipment) {{-- Ubah $pengiriman menjadi $shipments, $data menjadi $shipment --}}
                        <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">
                            {{ ($shipments->currentPage() - 1) * $shipments->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $shipment->tracking_number }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->sender->name }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->pickupAddress }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverName }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverAddress }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->orderDate ? $shipment->order->orderDate->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->weightKG }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($shipment->finalPrice, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->courier->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center font-semibold text-sm
                                @php $status = strtolower(trim($shipment->currentStatus)); @endphp
                                @if ($status === 'menunggu konfirmasi') text-gray-500
                                @elseif ($status === 'kurir ditugaskan') text-blue-600
                                @elseif ($status === 'kurir menuju lokasi penjemputan') text-yellow-600
                                @elseif ($status === 'paket telah di-pickup') text-purple-600
                                @elseif ($status === 'dalam perjalanan ke penerima') text-red-600
                                @elseif ($status === 'pesanan selesai') text-green-600
                                @else text-black
                                @endif">
                                {{ $shipment->currentStatus }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{-- Tampilkan gambar bukti jika ada, atau teks 'Menunggu Konfirmasi' --}}
                                @if ($shipment->delivery_proof)
                                    <img src="{{ asset('storage/' . $shipment->delivery_proof) }}" alt="Bukti" class="w-16 h-16 object-cover mx-auto rounded-md">
                                @else
                                    Menunggu Konfirmasi
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <button
                                        @click="showModal = false; showStatus = true; selectedData = {
                                            id: {{ $shipment->shipmentID }}, {{-- Penting untuk mengidentifikasi data saat update --}}
                                            resi: '{{ $shipment->tracking_number }}',
                                            pengirim: '{{ $shipment->order->sender->name }}',
                                            alamat_jemput: '{{ $shipment->order->pickupAddress }}',
                                            penerima: '{{ $shipment->order->receiverName }}',
                                            alamat_tujuan: '{{ $shipment->order->receiverAddress }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($shipment->order->orderDate)->format('Y-m-d') }}',
                                            berat: '{{ $shipment->weightKG }}',
                                            harga: '{{ number_format($shipment->finalPrice, 0, ',', '.') }}',
                                            kurir: '{{ $shipment->courier->name ?? 'N/A' }}',
                                            status: '{{ $shipment->currentStatus }}',
                                            tanggal_pengiriman: '{{ \Carbon\Carbon::parse($shipment->pickupTimestamp)->format('Y-m-d') }}',
                                            catatan: '{{ $shipment->noteadmin ?? 'Tidak ada catatan' }}' {{-- Pastikan ada kolom 'catatan' di DB atau berikan default --}}
                                        }"
                                        class="w-28 bg-red-500 text-white py-1 rounded text-xs hover:bg-red-600 shadow-md shadow-gray-700">
                                        Konfirmasi Status
                                    </button>
                                    <button
                                        @click="showStatus = false; showModal = true; selectedData = {
                                            id: {{ $shipment->shipmentID }},
                                            resi: '{{ $shipment->tracking_number }}',
                                            pengirim: '{{ $shipment->order->sender->name }}',
                                            alamat_jemput: '{{ $shipment->order->pickupAddress }}',
                                            penerima: '{{ $shipment->order->receiverName }}',
                                            alamat_tujuan: '{{ $shipment->order->receiverAddress }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($shipment->order->orderDate)->format('Y-m-d') }}',
                                            berat: '{{ $shipment->weightKG }}',
                                            harga: '{{ number_format($shipment->finalPrice, 0, ',', '.') }}',
                                            kurir: '{{ $shipment->courier->name ?? $shipment->courier->name ?? 'N/A' }}',
                                            status: '{{ $shipment->currentStatus }}',
                                            tanggal_pengiriman: '{{ \Carbon\Carbon::parse($shipment->pickupTimestamp)->format('Y-m-d') }}',
                                            catatan: '{{ $shipment->noteadmin ?? 'Tidak ada catatan' }}'
                                        }"
                                        class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-4 text-center text-gray-500">Tidak ada pengiriman untuk dikelola.</td>
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
        <div
            x-show="showModal"
            x-transition
            @keydown.escape.window="showModal = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                @click.away="showModal = false"
                class="bg-white p-6 rounded-lg shadow-md shadow-gray-700 w-[1000px] max-h-[90vh] overflow-y-auto"
            >
                <div class="flex justify-between items-center">
                    <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
                    <h5 class="text-xl font-sm flex-1 ml-4">Detail Pengiriman</h5>
                    <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 text-2xl leading-none">×</button>
                </div>

                <hr class="my-4 border-gray-300">

                <div class="text-sm space-y-4">
                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Resi</label>
                        <input type="text" readonly :value="selectedData.resi" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Nama Pengirim</label>
                        <input type="text" readonly :value="selectedData.pengirim" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Alamat Penjemputan</label>
                        <textarea readonly :value="selectedData.alamat_jemput" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Nama Penerima</label>
                        <input type="text" readonly :value="selectedData.penerima" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Alamat Tujuan</label>
                        <textarea readonly :value="selectedData.alamat_tujuan" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Berat (Kg)</label>
                        <input type="text" readonly :value="selectedData.berat + ' Kg'" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Harga</label>
                        <input type="text" readonly :value="'Rp ' + selectedData.harga" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Kurir</label>
                        <input type="text" readonly :value="selectedData.kurir" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Tanggal Pengiriman</label>
                        <input type="text" readonly :value="selectedData.tanggal_pengiriman" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Status</label>
                        <input type="text" readonly :value="selectedData.status" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div class="flex items-start gap-4">
                        <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Catatan</label>
                        <textarea readonly :value="selectedData.catatan" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button @click="showModal = false" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Status --}}
        <div x-show="showStatus" x-transition @keydown.escape.window="showStatus = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
            <div @click.away="showStatus = false" class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold">Konfirmasi Status</h2>
                    <button @click="showStatus = false" class="text-gray-500 text-2xl">&times;</button>
                </div>
                <form action="{{ route('shipment.updateStatus') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="shipmentID" :value="selectedData.id">
                    
                    {{-- PERBAIKAN 2: Baris untuk debugging, untuk melihat status yang diterima --}}
                    <div class="mb-4 p-2 bg-gray-100 rounded">
                        <p class="text-sm">Status Saat Ini: <strong x-text="selectedData.status || 'Tidak Terbaca'"></strong></p>
                    </div>

                    <div class="mb-4">
                        <label for="currentStatus" class="block text-sm font-medium text-gray-700 mb-1">Pilih Aksi Berikutnya:</label>
                        {{-- PERBAIKAN 3: Dropdown kini dibuat dengan perulangan x-for --}}
                        <select id="currentStatus" name="currentStatus" required x-model="nextStatus" class="select select-bordered w-full bg-white text-black">
                            <option value="" disabled>-- Pilih Aksi --</option>
                            <template x-for="option in availableOptions" :key="option.value">
                                <option :value="option.value" x-text="option.text"></option>
                            </template>
                        </select>
                        {{-- Pesan jika tidak ada opsi --}}
                        <template x-if="availableOptions.length === 0">
                            <p class="text-xs text-red-500 mt-1">Tidak ada aksi lanjutan untuk status ini.</p>
                        </template>
                    </div>

                    <div x-show="nextStatus === 'pesanan selesai'" x-transition class="mb-4">
                        <label for="delivery_proof" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pengiriman (Wajib):</label>
                        <input type="file" id="delivery_proof" name="delivery_proof" class="file-input file-input-bordered w-full" :required="nextStatus === 'pesanan selesai'">
                    </div>
                    <div class="flex justify-end pt-4 border-t mt-6">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection