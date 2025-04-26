@extends('layouts.kurir_page')
@section('title', 'Live Tracking')

@section('content')
<div class="absolute top-32 left-0 right-0 px-4" x-data="{ showResult: false }">
    <div class="max-w-[60rem] mx-auto flex flex-col gap-6">

        <!-- Card 1: Form Tracking -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex flex-col md:flex-row items-center gap-4">
                
                <!-- Input untuk kode tracking -->
                <div class="relative w-full md:flex-1">
                    <label class="block text-md font-semibold mb-1">Lacak Pengiriman</label>
                    <input type="text" value="11LU67UTW" 
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow focus:ring focus:ring-yellow-300 focus:outline-none">
                </div>

                <!-- Tombol untuk memulai tracking -->
                <button 
                    @click="showResult = true"
                    class="mt-5 md:mt-7 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-6 rounded-md shadow">
                    Mulai
                </button>
            </div>
        </div>

        <!-- Card 2: Hasil Tracking -->
        <div 
            x-show="showResult"
            x-transition
            class="bg-white rounded-xl shadow-2xl mb-20 p-6"
        >
            <div class="text-sm px-6 py-2">
                <!-- Judul dan informasi pengiriman -->
                <h2 class="text-center font-semibold text-lg mb-4">Live Tracking</h2>
                <br>
                <!-- Menampilkan detail pengiriman -->
                <div class="grid grid-cols-6 gap-x-2 gap-y-2 mb-4">
                    <!-- Baris pertama -->
                    <div class="col-span-1 font-semibold">Pengirim</div>
                    <div class="col-span-1">: Devia</div>
                    <div class="col-span-1 font-semibold">Penerima</div>
                    <div class="col-span-1">: Vicky</div>
                    <div class="col-span-1 font-semibold">Berat</div>
                    <div class="col-span-1">: 1 Kg</div>

                    <!-- Baris kedua -->
                    <div class="col-span-1 font-semibold">Alamat</div>
                    <div class="col-span-1">: Bida Asri 3</div>
                    <div class="col-span-1 font-semibold">Alamat Tujuan</div>
                    <div class="col-span-1">: Cendana</div>
                    <div class="col-span-1 font-semibold">Harga</div>
                    <div class="col-span-1">: Rp 40.000</div>
                </div>
            </div>

            <!-- Peta Lokasi -->
            <div class="w-full">
                <!-- Peta dummy -->
                <img 
                    src="{{ asset('images/kurir/maps.png') }}"
                    alt="Map Placeholder" 
                    class="w-full rounded-md" 
                />
            </div>

        </div> 
    </div> 
</div> 
@endsection
