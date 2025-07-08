<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light"> <!-- 👈 Tambah data-theme -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('SJ Courier Service', 'SJ Courier Service') }}</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     <!-- Leaflet CSS (TAMBAHAN) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Midtrans Snap JS (TAMBAHAN) -->
    <script type="text/javascript" src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-+sC4uXu7X5Pu0c5oI94sk/2Rdw2SnIpny9Wr8jQYuy+q1dA+H3Fb4B0DjJTx9M8S0frrEv4TbpiLzhdFqzH0WA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

     <style>
        /* Tambahkan style untuk map agar memiliki tinggi yang konsisten */
        .leaflet-map {
            height: 300px;
            border-radius: 0.5rem; /* Sesuai dengan rounded-lg daisyUI */
        }
        .main-content {
            margin-top: 0px; /* Sesuaikan dengan tinggi navbar */  
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100 flex flex-col"> <!-- Tambah bg abu-abu muda -->
    <!-- Navigation Header -->
    <x-header :user="Auth::user()" :links="getNavigationLinks(Auth::user())" />

    <!-- Page Content -->
    <main class="main-content flex-grow pt-20 md:px-10">
        {{ $slot }}
    </main>  

    <!-- Leaflet JS (TAMBAHAN) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Stack untuk script spesifik per halaman (TAMBAHAN) -->
    @stack('scripts')
</body>
</html>
