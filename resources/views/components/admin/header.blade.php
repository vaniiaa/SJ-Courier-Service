<header id="admin-header" class="admin-header shadow-md h-12 flex justify-between items-center fixed top-0 left-0 right-0 z-40 transition-all duration-300" style="background: linear-gradient(90deg, #FFA500 9%, #FFD45B 62%);">
    <button id="sidebar-toggle" class="text-gray-800 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500 ml-4">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex items-center flex-grow mx-4 ">
        <input type="text" placeholder="Search..." class="w-auto py-2 px-4 rounded-lg text-sm border bg-white border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500" />
    </div>

    <div class="ml-auto mr-4">
        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="px-5 py-2 rounded-lg text-black font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2" style="background:#FFD45B;" type="submit">Logout</button>
</form>
    </div>
</header>