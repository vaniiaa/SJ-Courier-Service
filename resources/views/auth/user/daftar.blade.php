@extends('layouts.auth')

@section('title', 'Register Kurir')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row items-center justify-center bg-white px-4 py-8">
    <div class="md:w-1/2 w-full bg-white flex items-center justify-center p-6">
        <img src="{{ asset('images/user/register.png') }}" alt="Register Kurir" class="w-full max-w-[500px] object-contain">
    </div>
    <div class="md:w-1/2 w-full p-6 md:p-12">
        <h2 class="text-xl md:text-2xl font-bold mb-6 text-center md:text-left">
            Register terlebih dahulu untuk menggunakan layanan
        </h2>
        <form action="#" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Masukkan Nama</label>
                <input type="text" name="name" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">No. Handphone</label>
                <input type="text" name="phone" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Konfirm Password</label>
                <input type="password" name="password_confirmation" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            </div>
            <button type="submit" class="w-full py-2 rounded text-black font-semibold" style="background: linear-gradient(to right, #FFA500, #FFD45B);">
                Register
            </button>
        </form>
    </div>
</div>
@endsection
