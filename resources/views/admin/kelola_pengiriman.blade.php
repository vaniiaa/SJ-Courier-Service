{{--
 * Nama File: kelola_pengiriman.php
 * Deskripsi: view ini berfungsi untuk menampilkan interface dari halaman kelola pengiriman. pada halaman ini, admin akan memanage semua 
 * pengiriman dengan menentkan seorang kurir untuk mengantar pengiriman customer.
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 --}}



@extends('layouts.admin')

{{-- Pastikan file layouts/admin.blade.php memiliki meta tag CSRF token di dalam bagian <head>:
<meta name="csrf-token" content="{{ csrf_token() }}">
Ini penting untuk keamanan Laravel, terutama saat mengirimkan shipment via AJAX. --}}

{{-- Sidebar --}}
@include('components.admin.sidebar')

@section('title', 'Kelola Pengiriman')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">

{{-- CSS Tambahan untuk Transisi Notifikasi & Gelap Header Modal --}}
<style>
    /* Style untuk transisi muncul dan menghilang pada notifikasi */
    .notification-transition {
        opacity: 0;
        transform: translateY(-20px); /* Mulai sedikit di atas */
        transition: opacity 0.5s ease-out, transform 0.5s ease-out; /* Transisi 0.5 detik */
    }

    .notification-transition.show {
        opacity: 1;
        transform: translateY(0); /* Kembali ke posisi normal */
    }

    /* CSS untuk menggelapkan header saat modal terbuka */
    .modal-open .admin-header {
        filter: brightness(0.5); /* Menggelapkan sebesar 50% */
        transition: filter 0.3s ease-in-out;
        pointer-events: none; /* Menonaktifkan interaksi klik pada header */
    }

    /* Pastikan elemen header Anda memiliki ID 'admin-header' */
    .header-darken {
        filter: brightness(0.5); /* Menggelapkan sebesar 50% */
        transition: filter 0.3s ease-in-out;
        pointer-events: none; /* Menonaktifkan interaksi klik */
    }

     nav[role="navigation"] > div > span,
    nav[role="navigation"] > div > a {
        margin-right: 8px; /* jarak antar tombol */
        padding: 6px 12px;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db; /* gray-300 */
        color: #1f2937; /* gray-800 */
    }

    nav[role="navigation"] > div > span[aria-current="page"] {
        background-color: #3b82f6; /* blue-500 */
        color: white;
        border-color: #3b82f6;
    }
</style>

<div class="absolute top-36 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('admin.kelola_pengiriman') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm bg-white" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

     {{-- Wadah ini akan menampung notifikasi yang dihasilkan oleh JavaScript --}}
        <div id="dynamic-notification-container" class="w-full mb-4"></div>

        {{-- Notifikasi Sukses (Session-based) --}}
        @if(session('success'))
            <div id="success-alert" class="notification-transition" role="alert"
                 style="background-color: #d1fae5; /* Warna hijau muda */
                        border: 1px solid #34d399; /* Border hijau */
                        color: #047857; /* Warna teks hijau gelap */
                        padding: 1rem; /* Padding internal */
                        border-radius: 0.5rem; /* Sudut membulat */
                        margin-bottom: 1rem; /* Margin bawah */
                        position: relative;
                        display: none; /* Sembunyikan secara default, akan ditampilkan oleh JS */">
                <strong style="font-weight: bold;">Berhasil!</strong>
                <span style="display: inline;">{{ session('success') }}</span>
                <span style="position: absolute; top: 0; bottom: 0; right: 0; padding: 1rem; cursor: pointer;" onclick="hideNotification(document.getElementById('success-alert'))">
                    <svg style="height: 1.5rem; width: 1.5rem; fill: currentColor; color: #10b981;" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.651a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.181l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.697z"/>
                    </svg>
                </span>
            </div>
        @endif

        {{-- Notifikasi Error (Session-based) --}}
        @if(session('error'))
            <div id="error-alert" class="notification-transition" role="alert"
                 style="background-color: #fee2e2; /* Warna merah muda */
                        border: 1px solid #f87171; /* Border merah */
                        color: #b91c1c; /* Warna teks merah gelap */
                        padding: 1rem; /* Padding internal */
                        border-radius: 0.5rem; /* Sudut membulat */
                        margin-bottom: 1rem; /* Margin bawah */
                        position: relative;
                        display: none; /* Sembunyikan secara default, akan ditampilkan oleh JS */">
                <strong style="font-weight: bold;">Gagal!</strong>
                <span style="display: inline;">{{ session('error') }}</span>
                <span style="position: absolute; top: 0; bottom: 0; right: 0; padding: 1rem; cursor: pointer;" onclick="hideNotification(document.getElementById('error-alert'))">
                    <svg class="fill-current" style="height: 1.5rem; width: 1.5rem; color: #ef4444;" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.651a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.181l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.697z"/>
                    </svg>
                </span>
            </div>
        @endif
        
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
                        <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                        <th class="px-4 py-2 text-left">Kurir</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $shipment)
                        <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}" id="row-{{ $shipment->shipmentID }}">
                            {{-- Menggunakan $loop->iteration untuk nomor urut --}}
                            <td class="px-4 py-2 text-center">
                                {{ ($shipments->currentPage() - 1) * $shipments->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $shipment->tracking_number }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->sender->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ Str::limit($shipment->order->pickupAddress, 20) }}</td>
                            <td class="px-4 py-2">{{ $shipment->order->receiverName }}</td>
                            <td class="px-4 py-2">{{ Str::limit($shipment->order->receiverAddress, 20) }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->created_at->format('d/m/y') }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->weightKG }} Kg</td>
                            <td class="px-4 py-2 text-center">Rp{{ number_format($shipment->finalPrice) }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->order->payments->first()->paymentMethod ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">{{ $shipment->courier->name ?? 'Belum Ditentukan' }}</td>
                            {{-- Menampilkan status pengiriman dengan warna berdasarkan status --}}
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
                            {{-- Tombol Aksi --}}
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Print --}}
                                    <button onclick="window.open('{{ route('admin.printResi', ['shipmentID' => $shipment->shipmentID]) }}', '_blank')" class="w-16 bg-green-500 hover:bg-green-600 text-white py-1 rounded text-xs shadow-md shadow-gray-700 flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4h12v5M6 14h12v6H6v-6zM6 14H4a2 2 0 01-2-2V9a2 2 0 012-2h16a2 2 0 012 2v3a2 2 0 01-2 2h-2" />
                                        </svg>
                                    </button>

                                    {{-- Tombol "Kurir" hanya muncul jika `kurir_id` belum ditentukan (NULL) --}}
                                    @if (empty($shipment->courierUserID))
                                    <button type="button" onclick="openModal(this)" class="w-16 bg-red-500 text-white py-1 rounded text-xs hover:bg-red-600 shadow-md shadow-gray-700"
                                        data-shipment-id="{{ $shipment->shipmentID }}"
                                        data-catatan-customer="{{ $shipment->order->notes ?? '' }}">
                                        Kurir
                                    </button>
                                    @endif

                                    {{-- Tombol "Detail" --}}
                                    <button type="button" onclick="showDetailModal(this)" class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700"
                                        data-resi="{{ $shipment->tracking_number }}"
                                        data-pengirim="{{ $shipment->order->sender->name ?? 'N/A' }}"
                                        data-alamat-jemput="{{ $shipment->order->pickupAddress }}"
                                        data-penerima="{{ $shipment->order->receiverName }}"
                                        data-alamat-tujuan="{{ $shipment->order->receiverAddress }}"
                                        data-kurir="{{ $shipment->courier->name ?? 'Belum Ditentukan' }}"
                                        data-tanggal="{{ $shipment->created_at->format('Y-m-d') }}"
                                        data-berat="{{ $shipment->weightKG }} Kg"
                                        data-harga="Rp{{ number_format($shipment->finalPrice) }}"
                                        data-status="{{ ucfirst($shipment->currentStatus) }}"
                                        data-catatan="{{ $shipment->order->notes ?? 'Tidak ada catatan' }}">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-4 text-center text-gray-500">Tidak ada shipment pengiriman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
<div id="modalDetail" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    {{-- Wadah untuk modal detail --}}
    <div class="bg-white p-4 rounded-lg shadow-md shadow-gray-700 w-[1000px] max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center">
            <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-full">
            <h5 class="text-xl font-semibold flex-1 ml-4">Detail Pengiriman</h5>
            <button onclick="closeDetailModal()" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <hr class="my-4 border-gray-300">

        <form id="detailForm" class="text-sm space-y-2"> {{-- space-y-2 untuk jarak vertikal lebih kecil --}}
            <div class="flex items-center gap-4">
                <label for="resiDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Resi</label> {{-- Lebar label diperlebar --}}
                <input type="text" id="resiDetail" name="resi" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly> {{-- py-1.5 untuk tinggi input lebih kecil, text-sm dipertahankan untuk keterbacaan --}}
            </div>
            <div class="flex items-center gap-4">
                <label for="pengirimDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Pengirim</label>
                <input type="text" id="pengirimDetail" name="pengirim" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatJemputDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Penjemputan</label>
                <textarea id="alamatJemputDetail" name="alamatJemput" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="penerimaDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Penerima</label>
                <input type="text" id="penerimaDetail" name="penerima" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatTujuanDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Tujuan</label>
                <textarea id="alamatTujuanDetail" name="alamatTujuan" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="tanggalDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Tanggal Pemesanan</label>
                <input type="text" id="tanggalDetail" name="tanggal" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="beratDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Berat (kg)</label>
                <input type="text" id="beratDetail" name="berat" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="hargaDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Harga (Rp)</label>
                <input type="text" id="hargaDetail" name="harga" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="kurirDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Kurir</label>
                <input type="text" id="kurirDetail" name="kurir" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="statusDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Status</label>
                <input type="text" id="statusDetail" name="status" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="catatanDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Catatan</label>
                <textarea id="catatanDetail" name="catatan" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
        </form>

        {{-- Tombol Tutup --}}
        <div class="flex justify-end mt-6">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal Tentukan Kurir --}}
<div id="modalTentukanKurir" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold">Penjadwalan Kurir</h5>
                <button class="text-gray-500 hover:text-gray-700 text-2xl" onclick="closeModal()">&times;</button>
            </div>
            <hr class="my-4">

            <form id="formTentukanKurir">
                @csrf
                <input type="hidden" id="shipmentIdToAssign" name="shipment_id" value="">

                {{-- Dropdown Wilayah --}}
                <div class="mb-4">
                    <label for="area_id" class="block text-sm font-medium text-gray-700">Wilayah Pengiriman</label>
                    <select id="area_id" name="area_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                       <option value="">Pilih wilayah...</option>
                       {{-- Loop untuk menampilkan semua area dari controller --}}
                       @foreach($deliveryAreas as $area)
                           <option value="{{ $area->area_id }}">{{ $area->area_name }}</option>
                       @endforeach
                    </select>
                </div>

                {{-- Dropdown Kurir (akan diisi oleh JavaScript) --}}
                <div class="mb-4">
                    <label for="kurir_id" class="block text-sm font-medium text-gray-700">Pilih Kurir</label>
                    <select id="kurir_id" name="kurir_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih wilayah dahulu...</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="pickupTimestamp" class="block text-sm font-medium text-gray-700">Tanggal Pengiriman</label>
                    <input type="datetime-local" id="pickupTimestamp" name="pickupTimestamp" required class="mt-1 block w-full px-3 py-2 border bg-white border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">                
                </div>

                <div class="mb-4">
                    <label for="catatanKurirModal" class="block text-sm font-medium text-gray-700">Catatan dari Customer</label>
                    <textarea id="catatanKurirModal" name="notes" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md focus:ring-blue-500 focus:border-blue-500" ></textarea>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeModal()" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow">Tugaskan Kurir</button>

                </div>
            </form>
        </div>
    </div>

 {{-- Script JavaScript --}}
    @push('scripts')
<script>
    // Menunggu seluruh konten halaman siap sebelum menjalankan JavaScript
    document.addEventListener('DOMContentLoaded', function() {

        // --- LOGIKA UNTUK MODAL PENJADWALAN KURIR ---
        const modalKurir = document.getElementById('modalTentukanKurir');
        const buttonsBukaModalKurir = document.querySelectorAll('.btn-buka-modal-kurir');

        buttonsBukaModalKurir.forEach(button => {
            button.addEventListener('click', function() {
                const shipmentId = this.dataset.shipmentId;
                const catatanCustomer = this.dataset.catatanCustomer;

                // Mengisi data ke dalam form di modal
                modalKurir.querySelector('#shipmentIdToAssign').value = shipmentId;
                modalKurir.querySelector('#catatanKurirModal').value = catatanCustomer;

                // Menampilkan modal
                modalKurir.classList.remove('hidden');
            });
        });

        // Event untuk tombol batal/close di modal kurir
        modalKurir.querySelector('button[onclick="closeModal()"]').addEventListener('click', function() {
            modalKurir.classList.add('hidden');
            modalKurir.querySelector('#formTentukanKurir').reset();
            modalKurir.querySelector('#kurir_id').innerHTML = '<option value="">Pilih wilayah dahulu...</option>';
        });


        // --- LOGIKA UNTUK MODAL DETAIL PENGIRIMAN ---
        const modalDetail = document.getElementById('modalDetail');
        const buttonsBukaModalDetail = document.querySelectorAll('.btn-buka-modal-detail');

        buttonsBukaModalDetail.forEach(button => {
            button.addEventListener('click', function() {
                const data = this.dataset; // Mengambil semua atribut data-*

                // Mengisi data ke setiap input di modal detail
                modalDetail.querySelector('#resiDetail').value = data.resi;
                modalDetail.querySelector('#pengirimDetail').value = data.pengirim;
                modalDetail.querySelector('#alamatJemputDetail').value = data.alamatJemput;
                modalDetail.querySelector('#penerimaDetail').value = data.penerima;
                modalDetail.querySelector('#alamatTujuanDetail').value = data.alamatTujuan;
                modalDetail.querySelector('#tanggalDetail').value = data.tanggal;
                modalDetail.querySelector('#beratDetail').value = data.berat;
                modalDetail.querySelector('#hargaDetail').value = data.harga;
                modalDetail.querySelector('#kurirDetail').value = data.kurir;
                modalDetail.querySelector('#statusDetail').value = data.status;
                modalDetail.querySelector('#catatanDetail').value = data.catatan;

                // Menampilkan modal
                modalDetail.classList.remove('hidden');
            });
        });

        // Event untuk tombol tutup di modal detail
        modalDetail.querySelector('button[onclick="closeDetailModal()"]').addEventListener('click', function() {
            modalDetail.classList.add('hidden');
        });


        // --- LOGIKA AJAX UNTUK DROPDOWN & SUBMIT FORM KURIR ---
        const areaSelect = document.getElementById('area_id');
        const kurirSelect = document.getElementById('kurir_id');
        const formTentukanKurir = document.getElementById('formTentukanKurir');

        // Event listener untuk dropdown area
        areaSelect.addEventListener('change', async function() {
            const selectedAreaId = this.value;
            kurirSelect.innerHTML = '<option value="">Memuat kurir...</option>';
            kurirSelect.disabled = true;

            if (!selectedAreaId) {
                kurirSelect.innerHTML = '<option value="">Pilih wilayah dahulu...</option>';
                return;
            }

            const url = `{{ url('/admin/couriers/by-area') }}/${selectedAreaId}`;
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Gagal mengambil data kurir');
                const couriers = await response.json();
                
                kurirSelect.innerHTML = '<option value="">Pilih kurir...</option>';
                if (couriers.length === 0) {
                    kurirSelect.innerHTML = '<option value="" disabled>Tidak ada kurir di area ini</option>';
                } else {
                    couriers.forEach(kurir => {
                        const option = document.createElement('option');
                        option.value = kurir.id;
                        option.textContent = kurir.name;
                        kurirSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching couriers:', error);
                kurirSelect.innerHTML = '<option value="" disabled>Gagal memuat kurir</option>';
            } finally {
                kurirSelect.disabled = false;
            }
        });

        // Event listener untuk submit form penugasan kurir
        formTentukanKurir.addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const response = await fetch("{{ route('shipments.assignCourier') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Terjadi kesalahan.');
                }
                
                alert('Sukses: ' + result.message);
                location.reload(); // Muat ulang halaman untuk melihat perubahan
                
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('Gagal: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Tugaskan Kurir';
            }
        });

    });
</script>
@endpush
@endsection