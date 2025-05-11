<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Kurir')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- DaisyUI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    {{-- Slick Carousel CSS --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    
    {{-- jQuery & Slick Carousel JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Navbar khusus kurir --}}
    @include('components.kurir.header')

    {{-- Main Content --}}
    <main class="w-full p-0 m-0">
        @yield('content')
    </main>

    {{-- Footer khusus kurir --}}
    @include('components.kurir.footer')

    @yield('scripts')
</body>
</html>
