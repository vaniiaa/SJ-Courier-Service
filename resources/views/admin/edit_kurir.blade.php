{{--
 * Nama File: edit_kurir.php
 * Deskripsi: view ini berfungsi untuk menampilkan interface dari halaman edit kurir yang berisi form untuk mengedit data kurir 
 * termasuk nama, email, no.hp, alamat, username dan password
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 --}}

@extends('layouts.admin')

@section('title', 'Edit Akun Kurir')

@section('content')

<div class="absolute top-36 left-0 right-0 px-4">
    <div class="w-full max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-6">
        {{-- Menampilkan pesan error validasi jika ada --}}
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

     {{-- Form untuk mengedit data kurir --}}
        <form action="{{ route('admin.kelola_kurir.update', $kurir->user_id) }}" method="POST" class="space-y-6">
            @csrf
            {{-- Menggunakan metode HTTP PUT untuk pembaruan data --}}

            @method('PUT')

            {{-- Input field untuk Nama Kurir --}}
            <div class="flex items-start mb-4">
                <label for="nama" class="w-40 font-medium flex items-center mt-2">Nama</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="name" name="name" value="{{ old('name', $kurir->name) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Input field untuk No. HP --}}
            <div class="flex items-start mb-4">
                <label for="no_hp" class="w-40 font-medium flex items-center mt-2">No. HP</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $kurir->phone) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Textarea untuk Alamat --}}
            <div class="flex items-start mb-4">
                <label for="alamat" class="w-40 font-medium flex items-center mt-2">Alamat</label>
                <span class="mx-2 mt-2">:</span>
                <textarea id="address" name="address"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('address', $kurir->address) }}</textarea>
            </div>

            {{-- Wilayah Pengiriman --}}
            <div class="flex items-start mb-4">
                <label for="area_id" class="w-40 font-medium flex items-center mt-2">Wilayah Pengiriman</label>
                <span class="mx-2 mt-2">:</span>
                <select id="area_id" name="area_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="">Pilih Wilayah</option>
                @foreach($areas as $area)
                    <option value="{{ $area->area_id }}" {{ old('area_id', $kurir->area_id) == $area->area_id ? 'selected' : '' }}>
            {{ $area->area_name }}
                    </option>
                @endforeach
                </select>
            </div>

            {{-- Input field untuk Email --}}          
            <div class="flex items-start mb-4">
                <label for="email" class="w-40 font-medium flex items-center mt-2">Email</label>
                <span class="mx-2 mt-2">:</span>
                <input type="email" id="email" name="email" value="{{ old('email', $kurir->email) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Input field untuk Password --}}
            <div class="flex items-start mb-4">
                <label for="password" class="w-40 font-medium flex items-center mt-2">Password</label>
                <span class="mx-2 mt-2">:</span>
                <input type="password" id="password" name="password"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Tombol untuk menyimpan perubahan --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-md shadow-gray-700">
                   Edit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
