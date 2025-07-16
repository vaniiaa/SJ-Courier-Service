@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Kelola Profile Admin')

@section('content')
<div class="absolute top-32 left-0 right-0 px-4" x-data="{ showModal: false, selectedData: {} }" @keydown.escape.window="showModal = false">
    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="max-w-[90rem] mx-auto mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="max-w-[90rem] mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Terdapat beberapa kesalahan pada input Anda.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="max-w-[90rem] h-[20rem] mx-auto bg-white rounded-xl shadow-xl p-6 flex flex-col md:flex-row gap-6 items-center">
        <!-- Avatar -->
        <div class="flex justify-center items-center w-40 h-40 bg-gray-100 rounded-full ml-10">
            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        <!-- Form -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 gap-x-10 w-full px-6">
            <!-- Nama -->
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium">Nama :</label>
                <div class="relative w-2/3">
                    <input
                        type="text"
                        value="Aulia Sabrina"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-300 focus:outline-none"
                    />
                    <!-- Ikon Edit -->
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- No. Handphone -->
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium">No. Handphone :</label>
                <div class="relative w-2/3">
                    <input
                        type="text"
                        value="085400445610"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-300 focus:outline-none"
                    />
                    <!-- Ikon Edit -->
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium">Email :</label>
                <div class="relative w-2/3">
                    <input
                        type="email"
                        value="aulia@gmail.com"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-300 focus:outline-none"
                    />
                    <!-- Ikon Edit -->
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kata Sandi -->
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium">Kata Sandi :</label>
                <div class="relative w-2/3">
                    <input
                        type="password"
                        value="*********"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-yellow-300 focus:outline-none"
                    />
                    <!-- Ikon Edit -->
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection