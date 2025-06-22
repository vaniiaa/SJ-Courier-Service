{{--
 * Nama File: dashboard_admin.php
 * Deskripsi: view ini berfungsi untuk menampilkan interface dari dashboard realtime pada halaman admin
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 --}}

@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Dashboard')

@section('content')

<div class="absolute top-36 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto space-y-8">
        
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            
            <div class="bg-red-500 shadow-md shadow-gray-700 rounded-xl p-4 flex justify-between items-center h-40">
                <div>
                    <h3 class="text-white text-lg font-semibold">Total Kurir</h3>
                    {{-- Menampilkan nilai variabel $totalKurir dari controller. --}}
                    <p class="text-white text-3xl font-bold">{{ $totalKurir }}</p>
                </div>
                <div class="bg-red-600 rounded-full p-3">
                    <i data-lucide="truck" class="w-8 h-8 text-white"></i>
                </div>
            </div>

            <div class="bg-blue-500 shadow-md shadow-gray-700 rounded-xl p-4 flex justify-between items-center h-40">
                <div>
                    <h3 class="text-white text-lg font-semibold">Total Pengiriman</h3>
                    {{-- Menampilkan total pengiriman yang sudah diformat angka. --}}
                    <p class="text-white text-3xl font-bold">{{ number_format($totalPengiriman, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-600 rounded-full p-3">
                    <i data-lucide="send" class="w-8 h-8 text-white"></i>
                </div>
            </div>

            <div class="bg-green-500 shadow-md shadow-gray-700 rounded-xl p-4 flex justify-between items-center h-40">
                <div>
                    <h3 class="text-white text-lg font-semibold">Total Wilayah</h3>
                    {{-- Menampilkan jumlah total wilayah dari controller. --}}
                    <p class="text-white text-3xl font-bold"></p>
                </div>
                <div class="bg-green-600 rounded-full p-3">
                    <i data-lucide="map-pin" class="w-8 h-8 text-white"></i>
                </div>
            </div>

        </div>

        <div class="bg-white shadow-md rounded-xl p-6">
    <h3 class="text-gray-700 text-lg font-semibold mb-4 text-center">Jumlah Pengiriman per Wilayah</h3>
    
    <div class="h-60 flex justify-center items-center">
        <canvas id="pengirimanChart" class="w-full max-w-3xl h-full"></canvas>
    </div>

    <p class="text-gray-500 text-sm mt-2 text-center">Data wilayah Batam</p>
</div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard_admin.js') }}"></script>
@endsection
