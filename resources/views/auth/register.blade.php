<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Inline Styles untuk memastikan tampilan yang konsisten -->
    <style>
        body {
            background-color: white;
        }
        .auth-box {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .form-input:focus {
            border-color: #FCD34D;
            box-shadow: 0 0 0 2px rgba(252, 211, 77, 0.25);
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-white flex items-center justify-center p-4">
        <div class="w-full max-w-md md:max-w-3xl bg-white rounded-lg auth-box overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Ilustrasi di sebelah kiri - hanya muncul di tampilan md ke atas -->
                <div class="hidden md:flex md:w-1/2 bg-white p-8 items-center justify-center">
                    <div class="w-full max-w-md">
                        <img src="{{ asset('images/user/register.png') }}" alt="Delivery Illustration" class="w-full">
                    </div>
                </div>

                <!-- Form register di sebelah kanan -->
                <div class="w-full md:w-1/2 p-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-center mb-6 text-black">Daftarkan Akun Anda</h1>
                    
                    <!-- Ilustrasi kecil hanya muncul di tampilan mobile -->
                    <div class="flex md:hidden justify-center mb-6">
                        <img src="{{ asset('images/user/register.png') }}" alt="Register" class="h-32">
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input id="name" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- phone -->
                        <div class="mb-4">
                            <label for="phone" class="block font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input id="phone" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required 
                                autocomplete="phone">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- address -->
                        <div class="mb-4">
                            <label for="address" class="block font-medium text-gray-700 mb-1">Alamat</label>
                            <input id="address" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="text" 
                                name="address" 
                                value="{{ old('address') }}" 
                                required 
                                autocomplete="address">
                            @error('address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input id="password_confirmation" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password">
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mb-6">
                            <span class="text-gray-800 text-sm">Sudah punya akun?</span>
                            <a class="text-blue-500 hover:text-blue-700 ml-1 text-sm" href="{{ route('login') }}">
                                Login
                            </a>
                        </div>

                        <!-- Register Button -->
                        <div>
                            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-3 px-4 rounded-md transition duration-200">
                                Daftar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>