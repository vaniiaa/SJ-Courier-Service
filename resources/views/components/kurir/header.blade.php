<style>
    html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;

    /* Header */
    .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 50;
        background: linear-gradient(to right, #FFA500, #FFD45B); 
        padding: 16px 20px;
    }

    /* Margin top konten utama */
    body {
        margin-top: 70px; 
    }
  }
</style>
<body>
<nav class="fixed-header text-black py-3 px-6 flex justify-between items-center shadow-md">
    <div class="flex items-center">
        <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain" />
        <span class="ml-2 font-bold">SJ Courier Service</span>
    </div>

    <ul class="flex space-x-6 items-center">
        <!-- Dropdown Layanan Kami -->
        <li x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-1">
                Layanan Kami
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 transform transition-transform duration-300"
            :class="open ? 'rotate-180' : ''"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
            </button>
            <ul 
                x-show="open" 
                @click.away="open = false" 
                class="absolute mt-2 bg-white text-black rounded-md border border-gray-200 py-2 w-44 z-50 border-b-4 border-yellow-400"
            >
                <li><a href="{{ asset('kurir/live_tracking')}}" class="block px-4 py-2 hover:bg-gray-100">Live Tracking</a></li>
                <li><a href="{{ asset('kurir/kelola_status')}}" class="block px-4 py-2 hover:bg-gray-100">Kelola Status</a></li>
                <li><a href="{{ asset('kurir/history_pengiriman_kurir')}}" class="block px-4 py-2 hover:bg-gray-100">History Pengiriman</a></li>
            </ul>
        </li>

       <!-- Dropdown Pengiriman -->
      <li x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center gap-1">
          Pengiriman
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 transform transition-transform duration-300"
            :class="open ? 'rotate-180' : ''"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <ul 
          x-show="open" 
          @click.away="open = false" 
          x-transition
          class="absolute mt-2 bg-base-100 text-black rounded-md border border-gray-200 py-2 w-44 z-50 border-b-4 border-yellow-400"
        >
          <li><a href="{{ asset('kurir/daftar_pengiriman')}}" class="block px-4 py-2 hover:bg-gray-100">Daftar Pengiriman</a></li>
        </ul>
      </li>

        <!-- Fitur lainnya -->
        <li><a href="{{ asset('/dashboard/kurir')}}">Dashboard</a></li>
        <li><a href="{{ asset('kurir/kelola_profile_kurir')}}">Akun</a></li>
        <li><a href="#">Logout</a></li>
    </ul>
</nav>
