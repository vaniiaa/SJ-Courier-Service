<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Kurir')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">
    
    {{-- Tailwind CSS & DaisyUI --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Link ke file CSS eksternal --}}
    <link rel="stylesheet" href="{{ asset('css/LayoutAdmin.css') }}">

    @yield('styles')
</head>

<body class="bg-gray-100 text-gray-900">

    {{-- Header Admin --}}
    @include('components.admin.header')

    {{-- Breadcrumbs Admin --}}
    <div id="breadcrumbs-container">
        @section('breadcrumbs')
            @include('components.admin.breadcrumbs', ['title' => View::getSection('title')])
        @show
    </div>

    {{-- Main Container --}}
    <div id="main-container">
        {{-- Main Content --}}
        <main class="transition-all duration-300">
            @yield('content')
        </main>
    </div>

    {{-- Sidebar Admin --}}
    @include('components.admin.sidebar')

    {{-- Link ke file JavaScript eksternal --}}
    <script src="{{ asset('js/LayoutAdmin.js') }}"></script>

    @yield('scripts')
</body>

</html>
