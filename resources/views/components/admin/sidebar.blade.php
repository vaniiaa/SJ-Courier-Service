<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SJ Courier Admin</title>
</head>
<body class="bg-gray-100">
<script src="/js/sidebar.js"></script>

<!-- Sidebar -->
<aside id="sidebar" class="flex flex-col justify-between w-64 h-[calc(100vh-3rem)] bg-white shadow border-r fixed top-12 left-0 z-50 transform -translate-x-full transition-transform duration-300">
    <div>
        <div class="p-4 border-b">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-900">John Doe</p>
                    <p class="text-xs text-gray-600">Admin</p>
                </div>
            </div>
        </div>

        <nav class="mt-4 px-4 space-y-2 text-sm text-gray-800">
            <p class="text-xs text-gray-500 uppercase">Dashboard</p>
            <a href="#" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
            <svg class="h-5 w-5 text-gray-700 ml-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7m-9 13V5" />
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="text-xs text-gray-500 uppercase mt-4">Menu Admin</p>
            <a href="{{ asset('admin/kelola_pengiriman') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
                <svg class="h-5 w-5 text-gray-700 ml-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 9h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kelola Pengiriman</span>
            </a>

            <a href="{{ asset('admin/kelola_kurir') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
                <svg class="h-5 w-5 text-gray-700 ml-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5V4H2v16h5m10 0a3 3 0 00-6 0m6 0a3 3 0 01-6 0" />
                </svg>
                <span>Kelola Akun Kurir</span>
            </a>

            <a href="#" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
                <svg class="h-5 w-5 text-gray-700 ml-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h1l2 9a1 1 0 001 1h11a1 1 0 001-1l2-9h1" />
                </svg>
                <span>Live Tracking</span>
            </a>

            <a href="{{ asset('admin/status_pengiriman') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
                <svg class="h-5 w-5 text-gray-700 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-6h13v6m-4-4h4m-6 4V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12m2 0a2 2 0 002 2h6a2 2 0 002-2" />
                </svg>
                <span>Status Pengiriman</span>
            </a>

            <a href="#" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
                <svg class="h-5 w-5 text-gray-700 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>History Pengiriman</span>
            </a>
        </nav>
    </div>

    <div class="px-4 py-4 border-t">
        <a href="/logout" class="flex items-center gap-3 text-red-600 hover:underline">
            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7" />
            </svg>
            <span>Logout</span>
        </a>
    </div>
</aside>

</body>
</html>
