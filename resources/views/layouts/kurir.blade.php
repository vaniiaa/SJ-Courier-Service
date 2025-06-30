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
    <style>
        .main-content {
            margin-top: 80px; /* Sesuaikan dengan tinggi navbar */  
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Navbar khusus kurir --}}
    @include('components.header', [
        'user' => Auth::user(),
        'links' => getNavigationLinks(Auth::user())
    ])

    {{-- Breadcrumbs Kurir --}}

    {{-- Main Content --}}
    <main class="main-content w-full p-0 m-0">
        @yield('content')
    </main>

    {{-- Footer khusus kurir --}}
    @include('components.kurir.footer')

    @yield('scripts')
</body>
</html>
