<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Kurir')</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Navbar khusus kurir --}}
    @include('components.kurir.header')

    {{-- Main Content --}}
    <main class="container mx-auto py-10">
        @yield('content')
    </main>

    {{-- Footer khusus kurir --}}
    @include('components.kurir.footer')

</body>
</html>