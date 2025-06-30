<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Kurir')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">

    {{-- Tailwind CSS CDN --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- DaisyUI CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    

</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen flex flex-col">
    {{-- Navbar khusus kurir --}}
    @include('components.header', [
        'user' => Auth::user(),
        'links' => getNavigationLinks(Auth::user())
    ])

    {{-- Breadcrumbs Kurir --}}
    @include('components.kurir.breadcrumbs_kurir', ['title' => View::getSection('title')])

    {{-- Main Content --}}
    <main class="container mx-auto py-6 px-4 pt-20">
        @yield('content')
    </main>
</div>
</body>
</html>
