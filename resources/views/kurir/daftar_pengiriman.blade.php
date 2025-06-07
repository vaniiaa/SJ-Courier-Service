@extends('layouts.kurir_page') {{-- Pastikan layout ini ada dan sesuai dengan struktur proyek Anda --}}

@section('title', 'Daftar Pengiriman Anda')

@section('content')

{{-- Tambahkan CSS untuk x-cloak di sini atau di file CSS Anda --}}
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
                    @forelse ($pengiriman as $index => $data)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">{{ $pengiriman->firstItem() + $index }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->resi }}</td>
                            <td class="px-4 py-2">{{ $data->nama_pengirim }}</td>
                            <td class="px-4 py-2">{{ $data->alamat_penjemputan }}</td>
                            <td class="px-4 py-2">{{ $data->nama_penerima }}</td>
                            <td class="px-4 py-2">{{ $data->alamat_tujuan }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->tanggal_pemesanan)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->berat }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($data->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->nama_kurir ?? 'Belum Ditentukan' }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->tanggal_pengiriman ?? 'Belum Ditentukan' }}</td>
                            <td class="px-4 py-2 text-center font-semibold text-sm
                                @if ($data->status_pengiriman === 'menunggu konfirmasi') text-gray-600
                                @elseif ($data->status_pengiriman === 'sedang dikirim') text-red-600
                                @elseif ($data->status_pengiriman === 'menuju alamat') text-blue-600
                                @elseif ($data->status_pengiriman === 'pesanan diterima') text-green-600
                                @endif">
                                {{ ucfirst($data->status_pengiriman) }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700"
                                        @click="showModal = true; selectedData = {
                                            resi: '{{ $data->resi }}',
                                            pengirim: '{{ $data->nama_pengirim }}',
                                            alamat_jemput: '{{ $data->alamat_penjemputan }}',
                                            penerima: '{{ $data->nama_penerima }}',
                                            alamat_tujuan: '{{ $data->alamat_tujuan }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($data->tanggal_pemesanan)->format('Y-m-d') }}',
                                            berat: '{{ $data->berat }}',
                                            harga: '{{ number_format($data->harga, 0, ',', '.') }}',
                                            kurir: '{{ $data->nama_kurir ?? 'Belum Ditentukan' }}',
                                            status: '{{ ucfirst($data->status_pengiriman) }}',
                                            catatan: '{{ $data->catatan ?? '' }}',
                                            tanggal_pengiriman: '{{ $data->tanggal_pengiriman ?? 'Belum Ditentukan' }}'
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

        {{-- Laravel Pagination Links --}}
        <div class="mt-4">
            {{ $pengiriman->links() }}
        </div>
    </div>

    {{-- Modal Detail Pengiriman --}}
    {{-- TAMBAHKAN x-cloak DI SINI --}}
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