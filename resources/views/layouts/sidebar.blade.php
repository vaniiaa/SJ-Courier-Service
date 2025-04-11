<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="{{ asset('js/sidebar.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    @include('components.admin.sidebar')   {{-- Sidebar --}}
    @include('components.admin.header')    {{-- Header --}}

    <main class="ml-0 p-6" id="mainContent">
        @yield('content')
    </main>

</body>
</html>
