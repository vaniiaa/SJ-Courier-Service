<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Kurir')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- DaisyUI CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    
    {{-- Alpine --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Navbar khusus kurir --}}
    @include('components.kurir.header')

    {{-- Breadcrumbs Kurir --}}
    @include('components.kurir.breadcrumbs_kurir', ['title' => View::getSection('title')])

    {{-- Main Content --}}
    <main class="container mx-auto py-6 px-4">
        @yield('content')
    </main>

</body>
</html>
