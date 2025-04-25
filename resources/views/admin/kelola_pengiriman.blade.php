@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Kelola Pengiriman')

@section('content')
<div class="absolute top-36 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" />
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
                <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                <th class="px-4 py-2 text-left">Kurir</th> 
                <th class="px-4 py-2 text-center">Status Pengiriman</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pengiriman = [
                    ['123456', 'John Doe', 'Jl. Punggur No.1', 'Dewi Lestari', 'Jl. Botania Raya', 'Cash', 'Belum Ditentukan', '2025-04-09', 2.5, 25000, 'menunggu konfirmasi'],
                    ['654321', 'Sarah Lee', 'Jl. Nongsa No.12', 'Budi Santoso', 'Jl. Tiban Indah', 'Transfer', 'Belum Ditentukan', '2025-04-08', 1.2, 15000, 'menunggu konfirmasi'],
                    ['111222', 'Rizal Pratama', 'Jl. Sadai No.45', 'Rani Wijaya', 'Jl. Batu Aji Lama', 'Cash', 'Belum Ditentukan', '2025-04-07', 3.0, 30000, 'menunggu konfirmasi'],
                    ['333444', 'Nina Fitria', 'Jl. Marina No.3', 'Yanto Pratama', 'Jl. Citra Mas', 'Transfer', 'Belum Ditentukan', '2025-04-06', 1.5, 18000, 'menunggu konfirmasi'],
                    ['555666', 'Tommy Lim', 'Jl. Barelang No.20', 'Sinta Ayu', 'Jl. Gajah Mada', 'Cash', 'Belum Ditentukan', '2025-04-05', 2.0, 22000, 'menunggu konfirmasi'],
                    ['777888', 'Indah Mulyani', 'Jl. Bengkong Laut', 'Agus Salim', 'Jl. Ruko Mega Legenda', 'Transfer', 'Faisal', '2025-04-04', 4.0, 40000, 'pesanan diterima'],
                    ['999000', 'Laila Rachmawati', 'Jl. Hang Nadim', 'Putri Wahyuni', 'Jl. Kepri Mall', 'Cash', 'Rick', '2025-04-03', 1.8, 20000, 'sedang dikirim'],
                    ['112233', 'Eko Setiawan', 'Jl. Baloi Permai', 'Rizky Hidayat', 'Jl. Nagoya Hill', 'Transfer', 'Daryl', '2025-04-02', 3.5, 35000, 'menuju alamat'],
                    ['E445566', 'Dani Saputra', 'Jl. Tiban Koperasi', 'Dian Anggraini', 'Jl. Puri Agung', 'Cash', 'Carl', '2025-04-01', 2.3, 27000, 'pesanan diterima'],
                    ['E778899', 'Mia Sutanto', 'Jl. Sungai Harapan', 'Fajar Nugroho', 'Jl. Taman Raya', 'Transfer', 'Judith', '2025-03-31', 1.9, 21000, 'sedang dikirim'],
                ];
            @endphp

            @foreach ($pengiriman as $index => $data)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 text-center">{{ $data[0] }}</td>
                    <td class="px-4 py-2">{{ $data[1] }}</td>
                    <td class="px-4 py-2">{{ $data[2] }}</td>
                    <td class="px-4 py-2">{{ $data[3] }}</td>
                    <td class="px-4 py-2">{{ $data[4] }}</td>
                    <td class="px-4 py-2">{{ $data[7] }}</td>
                    <td class="px-4 py-2 text-center">{{ $data[8] }}</td>
                    <td class="px-4 py-2 text-center">{{ number_format($data[9], 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $data[5] }}</td> 
                    <td class="px-4 py-2">{{ $data[6] }}</td> 
                    <td class="px-4 py-2 text-center font-semibold text-sm
                        @if ($data[10] === 'menunggu konfirmasi') text-gray-600
                        @elseif ($data[10] === 'sedang dikirim') text-red-600
                        @elseif ($data[10] === 'menuju alamat') text-blue-600
                        @elseif ($data[10] === 'pesanan diterima') text-green-600
                        @endif">
                        {{ ucfirst($data[10]) }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center gap-2">
                            @if ($index < 5)
                                <button class="w-28 bg-red-500 text-white py-1 rounded text-xs hover:bg-red-600 shadow-md shadow-gray-700" onclick="openModal()">Tentukan Kurir</button>
                            @endif
                            <button
                                class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700  px-4 py-2 "
                                onclick="showDetailModal(
                                    '{{ $data[0] }}', '{{ $data[1] }}', '{{ $data[2] }}',
                                    '{{ $data[3] }}', '{{ $data[4] }}', '{{ $data[5] }}',
                                    '{{ $data[6] }}', '{{ $data[7] }}', '{{ number_format($data[9], 0, ',', '.') }}',
                                    '{{ ucfirst($data[10]) }}'
                                )"
                            >
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        {{-- Pagination --}}
        <div class="flex justify-end mt-4 space-x-1 text-sm">
            <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]">&lt;&lt;</button>
            <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]">1</button>
            <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">2</button>
            <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">...</button>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div id="modalDetail" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-md shadow-gray-700 w-[1000px]">
        <div class="flex justify-between items-center">
            <img src="{{ asset('images/admin/logo.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
            <h5 class="text-xl font-semibold flex-1 ml-4">Detail Pengiriman</h5>
            <button onclick="closeDetailModal()" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <hr class="my-4 border-gray-300">
        <form id="detailForm" class="text-sm space-y-3">
            <div class="flex items-center gap-4">
                <label for="resiDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Resi</label>
                <input type="text" id="resiDetail" name="resi" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="pengirimDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Nama Pengirim</label>
                <input type="text" id="pengirimDetail" name="pengirim" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatJemputDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Alamat Penjemputan</label>
                <textarea id="alamatJemputDetail" name="alamatJemput" rows="2" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="penerimaDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Nama Penerima</label>
                <input type="text" id="penerimaDetail" name="penerima" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatTujuanDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Alamat Tujuan</label>
                <textarea id="alamatTujuanDetail" name="alamatTujuan" rows="2" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="beratDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Berat (kg)</label>
                <input type="number" id="beratDetail" name="berat" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                <label for="hargaDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Harga (Rp)</label>
                <input type="text" id="hargaDetail" name="harga" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                <label for="kurirDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Kurir</label>
                <input type="text" id="kurirDetail" name="kurir" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                <label for="statusDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Status</label>
                <select id="statusDetail" name="status" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="menunggu konfirmasi">Menunggu Konfirmasi</option>
                    <option value="sedang dikirim">Sedang Dikirim</option>
                    <option value="menuju alamat">Menuju Alamat</option>
                    <option value="pesanan diterima">Pesanan Diterima</option>
                </select>
            </div>
            <div class="flex items-center gap-4">
                <label for="tanggalDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Tanggal Pengiriman</label>
                <input type="text" id="tanggalDetail" name="tanggal" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center gap-4">
                 <label for="catatanDetail" class="w-32 text-left font-medium text-gray-700 text-sm after:content-[':']">Catatan</label>
                  <textarea id="catatanDetail" name="catatan" rows="2" class="flex-1 pl-4 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
        </form>
    </div>
</div>




{{-- Modal Tentukan Kurir --}}
<div id="modalTentukanKurir" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-md shadow-gray-700 w-[1000px]"> <div class="flex justify-between items-center">
            <img src="{{ asset('images/admin/logo.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
            <h5 class="text-xl font-semibold flex-1 ml-4">Penjadwalan Kurir</h5>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">&times;</button>
        </div>
        <hr class="my-4 border-gray-300">

        <form>
            <div class="mb-4">
                 <label for="wilayahPengiriman" class="block text-sm font-medium text-gray-700">Wilayah Pengiriman</label>
                 <select id="wilayahPengiriman" name="wilayahPengiriman" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                     <option value="">Pilih wilayah pengiriman...</option>
                     <option value="wilayahA">Batam Centre</option>
                     <option value="wilayahB">Botania</option>
                     <option value="wilayahC">Batu Aji</option>
                     <option value="wilayahD">Punggur</option>
                     <option value="wilayahE">Piayu</option>
                 </select>
             </div>

            <div class="mb-4">
                <label for="kurir" class="block text-sm font-medium text-gray-700">Pilih Kurir</label>
                <select id="kurir" name="kurir" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih kurir...</option>
                    <option value="kurirA">Faisal</option>
                    <option value="kurirB">Ricardo</option>
                    <option value="kurirC">William</option>
                    <option value="kurirD">Nick</option>
                    <option value="kurirE">Daniel</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tanggalPengiriman" class="block text-sm font-medium text-gray-700">Tanggal Pengiriman</label>
                <input type="date" id="tanggalPengiriman" name="tanggalPengiriman" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Masukkan catatan khusus" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                 <button type="button" onclick="closeModal()" class="text-sm py-3 px-5 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md shadow-gray-700">Batal</button>
                 <button type="submit"  class="text-sm py-3 px-5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md shadow-gray-700">Kirim</button>
             </div>
        </form>
</div>
</div>

{{-- Hubungkan file JavaScript eksternal --}}
<script src="{{ asset('js/kelola_pengiriman.js') }}"></script>
@endsection