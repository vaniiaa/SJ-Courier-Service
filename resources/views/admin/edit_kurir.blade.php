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
        <form action="{{ route('admin.update_kurir', $kurir->id) }}" method="POST" class="space-y-6">
            @csrf
            {{-- Menggunakan metode HTTP PUT untuk pembaruan data --}}

            @method('PUT')

            {{-- Input field untuk Nama Kurir --}}
            <div class="flex items-start mb-4">
                <label for="nama" class="w-40 font-medium flex items-center mt-2">Nama</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $kurir->nama) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Input field untuk Email --}}          
            <div class="flex items-start mb-4">
                <label for="email" class="w-40 font-medium flex items-center mt-2">Email</label>
                <span class="mx-2 mt-2">:</span>
                <input type="email" id="email" name="email" value="{{ old('email', $kurir->email) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>
            
            {{-- Input field untuk No. HP --}}
            <div class="flex items-start mb-4">
                <label for="no_hp" class="w-40 font-medium flex items-center mt-2">No. HP</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $kurir->no_hp) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

          {{-- Textarea untuk Alamat --}}
            <div class="flex items-start mb-4">
                <label for="alamat" class="w-40 font-medium flex items-center mt-2">Alamat</label>
                <span class="mx-2 mt-2">:</span>
                <textarea id="alamat" name="alamat"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('alamat', $kurir->alamat) }}</textarea>
            </div>

             {{-- Input field untuk Wilayah Pengiriman --}}
            <div class="flex items-start mb-4">
                <label for="wilayah_pengiriman" class="w-40 font-medium flex items-center mt-2">Wilayah Pengiriman</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="wilayah_pengiriman" name="wilayah_pengiriman" value="{{ old('wilayah_pengiriman', $kurir->wilayah_pengiriman) }}"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

                {{-- Input field untuk Username --}}
            <div class="flex items-start mb-4">
                <label for="username" class="w-40 font-medium flex items-center mt-2">Username</label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="username" name="username" value="{{ old('username', $kurir->username) }}"
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
