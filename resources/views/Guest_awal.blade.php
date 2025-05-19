<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SJ Courier Service - @yield('title')</title>
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
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    @include('components.guest.navigation')

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-courier-primary py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">LOGO</h3>
                    <p class="text-sm">
                        SJ City Courier adalah layanan pengiriman cepat, aman, dan terpercaya. Dengan sistem terintegrasi, kurir dapat memantau, memperbarui status, dan mengoptimasi pengiriman secara real-time.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Waktu Kerja</h3>
                    <p class="text-sm">
                        Senin - Jumat: 08:00 - 17:00<br>
                        Sabtu: 09:00 - 15:00<br>
                        Minggu & Hari Libur: Tutup
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Navigasi</h3>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:underline">Daftar Pengiriman</a></li>
                        <li><a href="#" class="hover:underline">Live Tracking</a></li>
                        <li><a href="#" class="hover:underline">Kelola Status</a></li>
                        <li><a href="#" class="hover:underline">Konfirmasi Pengiriman</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Privacy & TOS</h3>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="#" class="hover:underline">Terms of Service</a></li>
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                        <li><a href="#" class="hover:underline">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-4 border-t border-gray-700 text-sm text-center">
                &copy; {{ date('Y') }} SJ Courier Service. All rights reserved.
            </div>
        </div>
    </footer>

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
</body>
</html>