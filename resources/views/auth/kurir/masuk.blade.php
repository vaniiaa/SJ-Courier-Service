@extends('layouts.auth')

@section('title', 'Login Kurir')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row items-center justify-center bg-white px-4 py-8">
    <div class="md:w-1/2 w-full bg-white flex items-center justify-center p-6 md:p-8 pl-2 md:pl-100">
        <img src="{{ asset('images/kurir/login.jpg') }}" alt="Login Admin" class="w-full max-w-[400px] object-contain mt-[-20px]">
    </div>

    <div class="md:w-1/2 w-full p-6 md:p-12">
        <h2 class="text-xl md:text-2xl font-bold mb-6 text-center md:text-left">
            Login terlebih dahulu untuk menggunakan layanan
        </h2>
        {{-- Menampilkan pesan error validasi atau dari controller --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('kurir.login') }}" method="POST" class="space-y-4"> {{-- Ubah action ke route yang dinamai 'kurir.login' --}}
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror" value="{{ old('username') }}" autofocus />
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="border border-gray-300 rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror" />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col md:flex-row justify-between text-sm gap-2 md:gap-0">
                <a href="#" class="text-blue-500 hover:underline">Forgot Password?</a>
                <span class="text-center md:text-right">Belum punya Akun? <a href="#" class="text-blue-500 hover:underline">Register</a></span>
            </div>
            <button type="submit" class="w-full py-2 rounded text-black font-semibold" style="background: linear-gradient(to right, #FFA500, #FFD45B);">Login</button>
        </form>
        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-3 text-sm text-gray-500">Atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>
        <div class="text-sm text-center flex justify-between w-full mt-4">
            <a href="{{ route('admin.login') }}" class="text-blue-500 hover:underline">Login sebagai Admin</a>
            <a href="{{ route('user.login') }}" class="text-blue-500 hover:underline">Login sebagai User</a>
        </div>
    </div>
</div>
@endsection