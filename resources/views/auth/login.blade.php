<!-- resources/views/auth/login.blade.php -->
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
                        <img src="{{ asset('images/user/login.jpg') }}" alt="Delivery Illustration" class="w-full">
                    </div>
                </div>

                <!-- Form login di sebelah kanan -->
                <div class="w-full md:w-1/2 p-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-center mb-6 text-black">Silahkan Login</h1>
                    
                    <!-- Ilustrasi kecil hanya muncul di tampilan mobile -->
                    <div class="flex md:hidden justify-center mb-6">
                        <img src="{{ asset('images/user/login.jpg') }}" alt="Delivery Illustration" class="h-32">
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Username (Email) -->
                        <div class="mb-4">
                            <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" 
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username">
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
                                autocomplete="current-password">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Forgot Password & Register -->
                        <div class="flex flex-col sm:flex-row justify-between mb-6">
                            <div class="mb-2 sm:mb-0">
                                @if (Route::has('password.request'))
                                    <a class="text-gray-800 hover:text-gray-900 text-sm" href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="text-sm">
                                <span class="text-gray-800">Belum punya Akun?</span>
                                <a class="text-blue-500 hover:text-blue-700 ml-1" href="{{ route('register') }}">
                                    Register
                                </a>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div>
                            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-3 px-4 rounded-md transition duration-200">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>