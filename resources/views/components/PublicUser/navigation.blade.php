<nav class="navbar bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black top-0 left-0 shadow-md fixed z-50">
  <div class="flex-1">
    <a class="flex items-center gap-2">
      <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain" />
      <span class="font-bold text-base md:text-lg">SJ Courier Service</span>
    </a>
  </div>

  <div class="flex-none">
    <!-- Mobile Menu (Dropdown) -->
    <div class="dropdown dropdown-end lg:hidden">
      <label tabindex="0" class="btn btn-ghost btn-circle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>
      <ul tabindex="0"
          class="menu menu-sm dropdown-content mt-3 z-[100] p-2 shadow bg-white text-black rounded-box w-56">
        <li>
          <details>
            <summary>Layanan Kami</summary>
            <ul class="p-2">
              <li><a href="{{ asset('kurir/live_tracking') }}">Live Tracking</a></li>
              <li><a href="{{ asset('kurir/kelola_status') }}">Pengiriman</a></li>
            </ul>
          </details>
        </li>
        <li><a href="{{ asset('login') }}">Login</a></li>
        <li><a href="{{ asset('register') }}">Register</a></li>
      </ul>
    </div>

    <!-- Desktop Menu (Dropdown) -->
    <ul class="menu menu-horizontal px-1 hidden lg:flex text-black font-medium z-50">
  <!-- Layanan Kami -->
  <li>
    <details class="dropdown">
      <summary class="cursor-pointer px-4 py-2 hover:bg-yellow-300 rounded-md inline-flex items-center gap-1">
        Layanan Kami
      </summary>
      <ul class="p-2 bg-white text-black shadow-lg border border-yellow-300 rounded-md w-56 mt-2">
        <li><a href="{{ asset('kurir/live_tracking') }}" class="hover:bg-yellow-200">Live Tracking</a></li>
        <li><a href="{{ asset('kurir/kelola_status') }}" class="hover:bg-yellow-200">Pengiriman</a></li>
      </ul>
    </details>
  </li>

  <li><a href="{{ asset('login') }}" class="px-4 py-2 hover:bg-yellow-300 rounded-md">Login</a></li>
  <li><a href="{{ asset('register') }}" class="px-4 py-2 hover:bg-yellow-300 rounded-md">Register</a></li>
</ul>

  </div>
</nav>


