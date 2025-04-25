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
                    <p class="text-white text-3xl font-bold">80</p>
                </div>
                <div class="bg-red-600 rounded-full p-3">
                    <i data-lucide="truck" class="w-8 h-8 text-white"></i>
                </div>
            </div>

            <div class="bg-blue-500 shadow-md shadow-gray-700 rounded-xl p-4 flex justify-between items-center h-40">
                <div>
                    <h3 class="text-white text-lg font-semibold">Total Pengiriman</h3>
                    <p class="text-white text-3xl font-bold">1.723</p>
                </div>
                <div class="bg-blue-600 rounded-full p-3">
                    <i data-lucide="send" class="w-8 h-8 text-white"></i>
                </div>
            </div>

            <div class="bg-green-500 shadow-md shadow-gray-700 rounded-xl p-4 flex justify-between items-center h-40">
                <div>
                    <h3 class="text-white text-lg font-semibold">Total Wilayah</h3>
                    <p class="text-white text-3xl font-bold">12</p>
                </div>
                <div class="bg-green-600 rounded-full p-3">
                    <i data-lucide="map-pin" class="w-8 h-8 text-white"></i>
                </div>
            </div>

        </div>

        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-gray-700 text-lg font-semibold mb-4 ">Jumlah Pengiriman per Wilayah</h3>
            
            <div class="h-60"> {{-- Grafik dibatasi tinggi --}}
                <canvas id="pengirimanChart" class="w-full h-full"></canvas>
            </div>

            <p class="text-gray-500 text-sm mt-2">Data wilayah Batam</p>
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
<script src="{{ asset('js/dashboard_admin.js') }}"></script> {{-- Tambahkan ini --}}
@endsection
