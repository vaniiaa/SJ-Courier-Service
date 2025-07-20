<head>   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<aside id="sidebar" class="flex flex-col justify-between w-64 bg-white shadow border-r fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300" style="height: 100vh;">
    <div>
        <div class="p-4 border-b flex items-center gap-3" style="margin-top: -20px; background: linear-gradient(90deg, #FFA500 100%)">
            <img src="{{ asset('images/admin/logo1.jpg') }}" alt="Logo" class="h-10 w-10 object-contain" />
            <span class="text-lg font-bold text-black-600" style="margin-top: 10px;">SJ Courier Service</span>
        </div>

        <div class="p-4 border-b">
            <div class="flex items-center space-x-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase mt-4">Profile Admin</p>

                    {{-- TAMBAHKAN MENU PROFIL DI SINI --}}
                    <a href="{{ route('admin.profile.edit') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->routeIs('admin.profile.index') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role->role_name) }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <nav class="mt-4 px-4 space-y-2 text-sm text-gray-800">
            <p class="text-xs text-gray-500 uppercase">Dashboard</p>
            <a href="{{ asset('admin/dashboard_admin') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->is('admin/dashboard_admin') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.008-.008.017-.017.024-.026.16-.16.404-.16.564 0l4.965 4.966c.008.008.017.017.024.026.16.16.404.16.564 0l3.092-3.092" />
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="text-xs text-gray-500 uppercase mt-4">Menu Admin</p>

            <a href="{{ asset('admin/kelola_pengiriman') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->is('admin/kelola_pengiriman') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Kelola Pengiriman</span>
            </a>

            <a href="{{ asset('admin/kelola_kurir') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->is('admin/kelola_kurir') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5m6-10a3 3 0 100-6 3 3 0 000 6zm6 3a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <span>Kelola Akun Kurir</span>
            </a>

            <a href="{{ asset('admin/live_tracking_admin') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 7-9 12-9 12S3 17 3 10a9 9 0 1118 0z" />
                </svg>
                <span>Live Tracking</span>
            </a>

            <a href="{{ asset('admin/status_pengiriman') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->is('admin/status_pengiriman') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a3 3 0 00-3-3H7.5a3 3 0 00-3 3v13.5a3 3 0 003 3H16.5a3 3 0 003-3V10.5z" />
                </svg>
                <span>Status Pengiriman</span>
            </a>

            <a href="{{ asset('admin/history_pengiriman') }}" class="sidebar-link flex items-center space-x-4 p-3 rounded-lg transition hover:bg-gradient-to-r hover:from-yellow-400 hover:to-orange-300 {{ request()->is('admin/history_pengiriman') ? 'bg-gradient-to-r from-yellow-400 to-orange-300 text-black font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>History Pengiriman</span>
            </a>
        </nav>
    </div>
</aside>
