<aside id="sidebar" class="flex flex-col justify-between w-64 bg-white shadow border-r fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300" style="height: 100vh;">
    <div>
    <div class="p-4 border-b flex items-center gap-3" style="margin-top: -20px; background: linear-gradient(90deg, #FFA500 100%)">
    <img src="{{ asset('images/admin/logo.jpg') }}" alt="Logo" class="h-8 w-8 object-contain" />
    <span class="text-lg font-bold text-black-600" style="margin-top: 10px;">SJ Courier Service</span>
</div>
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
      <!-- Dashboard -->
      <p class="text-xs text-gray-500 uppercase">Dashboard</p>
      <a href="{{ asset('admin/dashboard_admin')}}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.008-.008.017-.017.024-.026.16-.16.404-.16.564 0l4.965 4.966c.008.008.017.017.024.026.16.16.404.16.564 0l3.092-3.092" />
        </svg>
        <span>Dashboard</span>
      </a>


            <p class="text-xs text-gray-500 uppercase mt-4">Menu Admin</p>
           <!-- Kelola Pengiriman -->
      <a href="{{ asset('admin/kelola_pengiriman') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Kelola Pengiriman</span>
      </a>

            <!-- Kelola Akun Kurir -->
      <a href="{{ asset('admin/kelola_kurir') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5m6-10a3 3 0 100-6 3 3 0 000 6zm6 3a2 2 0 100-4 2 2 0 000 4z" />
        </svg>
        <span>Kelola Akun Kurir</span>
      </a>

           <!-- Live Tracking -->
      <a href="#" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 7-9 12-9 12S3 17 3 10a9 9 0 1118 0z" />
        </svg>
        <span>Live Tracking</span>
      </a>

           
      <!-- Status Pengiriman -->
      <a href="{{ asset('admin/status_pengiriman') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a3 3 0 00-3-3H7.5a3 3 0 00-3 3v13.5a3 3 0 003 3H16.5a3 3 0 003-3V10.5z" />
        </svg>
        <span>Status Pengiriman</span>
      </a>

            
      <!-- History Pengiriman -->
      <a href="{{ asset('admin/history_pengiriman') }}" class="sidebar-link flex items-center space-x-4 hover:text-orange-600">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700 ml-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
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