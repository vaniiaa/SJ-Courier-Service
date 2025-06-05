<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light"> <!-- 👈 Tambah data-theme -->

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
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100"> <!-- Tambah bg abu-abu muda -->
    <!-- Navigation Header -->
    <x-header :user="Auth::user()" :links="$links" />

    <!-- Page Content -->
    <main class="flex-grow pt-20 md:px-10">
        {{ $slot }}
    </main>

    <!--Footer-->
    <x-footer :menus="[
        [
            'title' => 'Waktu Kerja',
            'content' => '<p>Senin - Jumat: 08:00 - 18:00</p><p>Sabtu: 09:00 - 15:00</p><p>Minggu: Libur</p>',
        ],
        [
            'title' => 'Layanan Kami',
            'items' => [
                ['label' => 'Live Tracking', 'url' => '#'],
                ['label' => 'Pengiriman', 'url' => '#'],
                ['label' => 'Cek Tarif', 'url' => '#'],
                ['label' => 'History Pengiriman', 'url' => '#'],
            ],
        ],
        [
            'title' => 'Privacy & TOS',
            'items' => [
                ['label' => 'Kebijakan Privasi', 'url' => '#'],
                ['label' => 'Syarat & Ketentuan', 'url' => '#'],
            ],
        ],
    ]" />
</body>
</html>
