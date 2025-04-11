@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'History Pengiriman')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<div class="absolute top-32 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" />
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
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $pengiriman = [
                        ['123456', 'John Doe', 'Jl. Punggur No.1', 'Dewi Lestari', 'Jl. Botania Raya', 'Kurir A', '2025-04-09', 2.5, 25000, 'pesanan diterima'],
                        ['654321', 'Sarah Lee', 'Jl. Nongsa No.12', 'Budi Santoso', 'Jl. Tiban Indah', 'Kurir B', '2025-04-08', 1.2, 15000, 'pesanan diterima'],
                        ['111222', 'Rizal Pratama', 'Jl. Sadai No.45', 'Rani Wijaya', 'Jl. Batu Aji Lama', 'Kurir C', '2025-04-07', 3.0, 30000, 'pesanan diterima'],
                        ['333444', 'Nina Fitria', 'Jl. Marina No.3', 'Yanto Pratama', 'Jl. Citra Mas', 'Kurir D', '2025-04-06', 1.5, 18000, 'pesanan diterima'],
                        ['555666', 'Tommy Lim', 'Jl. Barelang No.20', 'Sinta Ayu', 'Jl. Gajah Mada', 'Kurir E', '2025-04-05', 2.0, 22000, 'pesanan diterima'],
                        ['777888', 'Indah Mulyani', 'Jl. Bengkong Laut', 'Agus Salim', 'Jl. Ruko Mega Legenda', 'Kurir F', '2025-04-04', 4.0, 40000, 'pesanan diterima'],
                        ['999000', 'Laila Rachmawati', 'Jl. Hang Nadim', 'Putri Wahyuni', 'Jl. Kepri Mall', 'Kurir G', '2025-04-03', 1.8, 20000, 'pesanan diterima'],
                        ['112233', 'Eko Setiawan', 'Jl. Baloi Permai', 'Rizky Hidayat', 'Jl. Nagoya Hill', 'Kurir H', '2025-04-02', 3.5, 35000, 'pesanan diterima'],
                        ['E445566', 'Dani Saputra', 'Jl. Tiban Koperasi', 'Dian Anggraini', 'Jl. Puri Agung', 'Kurir I', '2025-04-01', 2.3, 27000, 'pesanan diterima'],
                        ['E778899', 'Mia Sutanto', 'Jl. Sungai Harapan', 'Fajar Nugroho', 'Jl. Taman Raya', 'Kurir J', '2025-03-31', 1.9, 21000, 'pesanan diterima'],
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
                        <td class="px-4 py-2">{{ $data[5] }}</td>
                        <td class="px-4 py-2">{{ $data[6] }}</td>
                        <td class="px-4 py-2 text-center">{{ $data[7] }}</td>
                        <td class="px-4 py-2 text-center">{{ number_format($data[8], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center font-semibold text-sm text-green-600">
                            {{ ucfirst($data[9]) }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="/path/to/download/{{ $data[0] }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs shadow-md shadow-gray-700" title="Unduh">
                                    <i class="fas fa-download"></i>
                                </a>
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
@endsection
