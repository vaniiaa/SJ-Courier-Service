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

    <form action="{{ route('admin.simpan_kurir') }}" method="POST" class="space-y-6">
        @csrF

            {{-- Nama --}}
            <div class="flex items-start mb-4">
                <label for="nama" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.125h15.002M4.501 20.125V5.25m15.002 0v14.875" />
                    </svg>
                    Nama
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="nama" name="nama" placeholder="Tulis nama lengkap Pengguna"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
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

            {{-- No. HP --}}
            <div class="flex items-start mb-4">
                <label for="no_hp" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h8.25A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25h-3a2.25 2.25 0 00-2.25 2.25v13.5a2.25 2.25 0 002.25 2.25h3z" />
                    </svg>
                    No. HP
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP yang aktif"
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
                <textarea id="alamat" name="alamat" rows="3" placeholder="Tulis alamat lengkap Pengguna"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500"></textarea>
            </div>

            {{-- Wilayah Pengiriman --}}
            <div class="flex items-start mb-4">
                <label for="wilayah_pengiriman" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.1046 0 2-.8954 2-2s-.8954-2-2-2-2 .8954-2 2 .8954 2 2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 22s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
                    </svg>
                    Wilayah Pengiriman
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="wilayah_pengiriman" name="wilayah_pengiriman" placeholder="Tulis wilayah pengiriman"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-md shadow-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Nama User --}}
            <div class="flex items-start mb-4">
                <label for="username" class="w-40 font-medium flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                    </svg>
                    Nama User
                </label>
                <span class="mx-2 mt-2">:</span>
                <input type="text" id="username" name="username" placeholder="Tulis nama pengguna yang unik"
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