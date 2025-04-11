<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SJ Courier - Admin')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Header Admin --}}
    @include('components.admin.header')

    {{-- Breadcrumbs Admin --}}
    @include('components.admin.breadcrumbs', ['title' => View::getSection('title')])

    {{-- Main Content --}}
    <main class="container mx-auto py-6 px-4">
        @yield('content')
    </main>

</body>
</html>
