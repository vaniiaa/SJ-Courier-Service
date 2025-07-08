<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SJ Courier Service - @yield('title')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite('resources/css/app.css')
    <style>
        .bg-courier-primary {
            background-color: #ffc107;
        }
        .bg-courier-secondary {
            background-color: #1e88e5;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
   <x-header :user="Auth::user()" :links="$links" />

    <!-- Main Content -->
    <main class="flex-grow pt-20 {{ request()->is('dashboard') ? 'px-0' : 'px-4 md:px-10' }}">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.PublicUser.footer')

    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const menuClose = document.getElementById('menuClose');
            const mobileMenu = document.getElementById('mobileMenu');
            const backdrop = document.querySelector('.navbar-backdrop');
            
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            });
            
            menuClose.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
            
            backdrop.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });
    </script>

    {{-- Stack untuk script spesifik per halaman --}}
    @stack('scripts')
</body>
</html>