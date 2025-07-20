<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SJ Courier - Admin')</title>
    <link rel="icon" href="{{ asset('images/admin/logo2.jpg') }}" type="image/jpeg">

    {{-- Tailwind CSS}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Link ke file CSS eksternal --}}
    <link rel="stylesheet" href="{{ asset('css/LayoutAdmin.css') }}">

    @yield('styles')
</head>

@stack('scripts')

<body class="bg-gray-100 min-h-screen text-gray-900">

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
