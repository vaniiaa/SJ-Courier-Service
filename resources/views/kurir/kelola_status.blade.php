@extends('layouts.kurir_page')
@section('title', 'Kelola Status')

@section('content')

    <div class="absolute top-32 left-0 right-0 px-4" x-data="{ showModal: false, showStatus: false, selectedData: {} }" @keydown.escape.window="showModal = false; showStatus = false">
        <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg mb-20 p-4">
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
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Berat (Kg)</th>
                            <th class="px-4 py-2 text-center">Harga (Rp)</th>
                            <th class="px-4 py-2 text-center">Kurir</th>
                            <th class="px-4 py-2 text-center">Status Pengiriman</th>
                            <th class="px-4 py-2 text-center">Bukti Pengiriman</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $pengiriman = [
                                ['123456', 'John Doe', 'Jl. Punggur No.1', 'Dewi Lestari', 'Jl. Botania Raya', '2025-04-09', 2, 25000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['654321', 'Sarah Lee', 'Jl. Nongsa No.12', 'Budi Santoso', 'Jl. Tiban Indah', '2025-04-08', 1, 15000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['111222', 'Rizal Pratama', 'Jl. Sadai No.45', 'Rani Wijaya', 'Jl. Batu Aji Lama', '2025-04-07', 3, 30000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['333444', 'Nina Fitria', 'Jl. Marina No.3', 'Yanto Pratama', 'Jl. Citra Mas', '2025-04-06', 5, 18000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['555666', 'Tommy Lim', 'Jl. Barelang No.20', 'Sinta Ayu', 'Jl. Gajah Mada', '2025-04-05', 2, 22000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['777888', 'Indah Mulyani', 'Jl. Bengkong Laut', 'Agus Salim', 'Jl. Ruko Mega Legenda', '2025-04-04', 4, 40000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['999000', 'Laila Rachmawati', 'Jl. Hang Nadim', 'Putri Wahyuni', 'Jl. Kepri Mall', '2025-04-03', 1, 20000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['112233', 'Eko Setiawan', 'Jl. Baloi Permai', 'Rizky Hidayat', 'Jl. Nagoya Hill', '2025-04-02', 3, 35000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['E445566', 'Dani Saputra', 'Jl. Tiban Koperasi', 'Dian Anggraini', 'Jl. Puri Agung', '2025-04-01', 2, 27000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
                                ['E778899', 'Mia Sutanto', 'Jl. Sungai Harapan', 'Fajar Nugroho', 'Jl. Taman Raya', '2025-03-31', 1, 21000, 'Georgy Malfoy', 'sedang dikirim', 'Menunggu Konfirmasi'],
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
                                <td class="px-4 py-2 text-center">{{ $data[6] }}</td>
                                <td class="px-4 py-2 text-center">{{ number_format($data[7], 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-center">{{ $data[8] }}</td>
                                <td class="px-4 py-2 text-center font-semibold text-sm
                                    @if ($data[9] === 'menunggu konfirmasi') text-gray-600
                                    @elseif ($data[9] === 'sedang dikirim') text-red-600
                                    @elseif ($data[9] === 'menuju alamat') text-blue-600
                                    @elseif ($data[9] === 'pesanan diterima') text-green-600
                                    @endif">
                                    {{ ucfirst($data[9]) }}
                                </td>
                                <td class="px-4 py-2 text-center">{{ $data[10] }}</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button 
                                            @click="showStatus = true; selectedData = { 
                                                resi: '{{ $data[0] }}', 
                                                pengirim: '{{ $data[1] }}', 
                                                alamat_jemput: '{{ $data[2] }}', 
                                                penerima: '{{ $data[3] }}', 
                                                alamat_tujuan: '{{ $data[4] }}', 
                                                tanggal: '{{ $data[5] }}', 
                                                berat: '{{ $data[6] }}', 
                                                harga: '{{ number_format($data[7], 0, ',', '.') }}', 
                                                kurir: '{{ $data[8] }}', 
                                                status: '{{ $data[9] }}', 
                                                tanggal_pengiriman: '{{ $data[5] }}', 
                                                catatan: 'barang fragile, tangani dengan hati-hati' 
                                            }" 
                                            class="w-28 bg-red-500 text-white py-1 rounded text-xs hover:bg-red-600 shadow-md shadow-gray-700">
                                            Konfirmasi Status
                                        </button>
                                        <button 
                                            @click="showModal = true; selectedData = { 
                                                resi: '{{ $data[0] }}', 
                                                pengirim: '{{ $data[1] }}', 
                                                alamat_jemput: '{{ $data[2] }}', 
                                                penerima: '{{ $data[3] }}', 
                                                alamat_tujuan: '{{ $data[4] }}', 
                                                tanggal: '{{ $data[5] }}', 
                                                berat: '{{ $data[6] }}', 
                                                harga: '{{ number_format($data[7], 0, ',', '.') }}', 
                                                kurir: '{{ $data[8] }}', 
                                                status: '{{ $data[9] }}', 
                                                tanggal_pengiriman: '{{ $data[5] }}', 
                                                catatan: 'barang fragile, tangani dengan hati-hati' 
                                            }" 
                                            class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700">
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
                <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]"><<</button>
                <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]">1</button>
                <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">2</button>
                <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">...</button>
            </div>

            <!-- Modal Detail Pengiriman -->
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
                    <!-- Header -->
                    <div class="flex justify-between items-center">
                        <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
                        <h5 class="text-xl font-sm flex-1 ml-4">Detail Pengiriman</h5>
                        <button @click="showModal = false" class="text-gray-600 hover:text-gray-800 text-2xl leading-none">×</button>
                    </div>

                    <hr class="my-4 border-gray-300">

                    <div class="text-sm space-y-4">
                        <!-- Field Row -->
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

                        <div class="flex items-start gap-4">
                            <label class="w-40 text-sm font-medium text-gray-700 after:content-[':']">Bukti Pengiriman</label>
                            <div class="flex-1">
                                <img :src="selectedData.bukti ?? '{{ asset('images/kurir/welcome.jpg') }}'" alt="Bukti Pengiriman" class="w-32 rounded-md">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end mt-6">
                        <button @click="showModal = false" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Status -->
            <div
                x-show="showStatus"
                x-transition
                @keydown.escape.window="showStatus = false"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            >
                <div
                    @click.away="showStatus = false"
                    class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 min-h-[40px] overflow-visible relative"
                >
                    <!-- Tombol Tutup (X) di kiri atas -->
                    <button
                        @click="showStatus = false"
                        class="absolute top-2 right-2 text-gray-600 text-xl font-bold focus:outline-none"
                        aria-label="Tutup"
                    >
                    ×
                    </button>

                    <!-- Header dengan logo dan judul -->
                    <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-200">
                        <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
                        <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Status</h2>
                    </div>

                    <!-- Body: Form -->
                    <div x-data="{ open: false, selected: 'Pilih Status' }" x-cloak class="px-6 py-5 grid grid-cols-1 gap-6 text-gray-700 text-sm">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Dropdown Status -->
                            <div class="mb-4 relative">
                                <label for="status" class="font-semibold">Status Pengiriman:</label>
                                <button type="button" @click="open = !open" class="w-full input input-neutral border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-left pl-4 pr-10 py-2 relative">
                                    <span x-text="selected"></span>
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 transform transition-transform duration-300"
                                            :class="open ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <ul x-show="open" @click.away="open = false" x-transition class="absolute w-full mt-1 bg-white border border-gray-300 rounded shadow z-10">
                                    <li><a href="#" @click.prevent="selected = 'Sedang Dikirim'; open = false" class="block px-4 py-2 hover:bg-gray-100">Sedang Dikirim</a></li>
                                    <li><a href="#" @click.prevent="selected = 'Menuju Alamat'; open = false" class="block px-4 py-2 hover:bg-gray-100">Menuju Alamat</a></li>
                                    <li><a href="#" @click.prevent="selected = 'Pesanan Selesai'; open = false" class="block px-4 py-2 hover:bg-gray-100">Pesanan Selesai</a></li>
                                </ul>

                                <input type="hidden" name="status" :value="selected">
                            </div>

                            <!-- Upload Bukti: hanya muncul jika 'Pesanan Selesai' -->
                            <div x-show="selected === 'Pesanan Selesai'" class="mb-4" x-transition>
                                <label for="bukti_pengiriman" class="font-semibold">Bukti Pengiriman:</label>
                                <input type="file" id="bukti_pengiriman" name="bukti_pengiriman" class="file file-input w-full border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 pl-4" />
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end items-center gap-2 border-t border-gray-200 pt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection