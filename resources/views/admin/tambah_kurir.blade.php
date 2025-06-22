{{--
 * Nama File: edit_kurir.php
 * Deskripsi: view ini berfungsi untuk menampilkan interface dari halaman edit kurir yang berisi form untuk mengedit data kurir 
 * termasuk nama, email, no.hp, alamat, username dan password
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 --}}



@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Tambah Akun Kurir')

@section('content')
<div class="absolute top-36 left-0 right-0 px-4">
    <div class="w-full max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-6">
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kelola_kurir.store') }}" method="POST" class="space-y-6">
        @csrf

            {{-- Nama --}}
            <div class="flex items-start mb-4">
                <label for="nama" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.125h15.002M4.501 20.125V5.25m15.002 0v14.875" />
                    </svg>
                    Nama
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="name" name="name" placeholder="Tulis nama lengkap Pengguna"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{--Phone--}}
            <div class="flex items-start mb-4">
                <label for="no_hp" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a3 3 0 00-3-3h-3a3 3 0 00-3 3v3.75M5.25 10.5h13.5A2.25 2.25 0 0121 12.75v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18.75v-6A2.25 2.25 0 015.25 10.5z" />
                    </svg>
                    No HP
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="phone" name="phone" placeholder="Tulis nomor handphone"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>
            
            {{-- Alamat --}}
            <div class="flex items-start mb-4">
                <label for="alamat" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4.5 10.5v9.75a.75.75 0 00.75.75h3.75a.75.75 0 00.75-.75V15a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v5.25a.75.75 0 00.75.75h3.75a.75.75 0 00.75-.75V10.5" />
                    </svg>
                    Alamat
                </label>
                <span class="mx-2 mt-2">:</span>
                <textarea id="address" name="address" rows="3" placeholder="Tulis alamat lengkap Pengguna"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500"></textarea>
            </div>

            {{-- Wilayah Pengiriman --}}
            <div class="flex items-start mb-4">
                <label for="area_id" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.75l-7.5-7.5a9 9 0 1115 0l-7.5 7.5zM12 9v3m0 3h.01M12 6a3 3 0 100 6 3 3 0 000-6z" />
                    </svg>
                    Wilayah Pengiriman
                </label>
                <span class="mx-2 mt-2">:</span>
                <select id="area_id" name="area_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <option value="">Pilih Wilayah</option>
                @foreach($areas as $area)
                <option value="{{ $area->area_id }}">{{ $area->area_name }}</option>
                @endforeach
                </select>
            </div>

            {{-- Email --}}
            <div class="flex items-start mb-4">
                <label for="email" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.167a2.25 2.25 0 01-2.36 0L3.32 8.913a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    Email
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="email" id="email" name="email" placeholder="Masukkan email yang valid"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Sandi --}}
            <div class="flex items-start mb-4">
                <label for="password" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a3 3 0 00-3-3h-3a3 3 0 00-3 3v3.75M5.25 10.5h13.5A2.25 2.25 0 0121 12.75v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18.75v-6A2.25 2.25 0 015.25 10.5z" />
                    </svg>
                    Sandi
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="password" id="password" name="password" placeholder="Tulis sandi minimal 6 karakter"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 hover:bg-Edit-600 text-white rounded-md shadow-md transition duration-200">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection]