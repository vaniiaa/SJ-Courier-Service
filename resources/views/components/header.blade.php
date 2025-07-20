@props(['user', 'links'])

<nav class="navbar bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black top-0 left-0 shadow-md fixed w-full z-50">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
        <!-- Logo di kiri -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/admin/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain" />
            <span class="font-bold text-base md:text-lg">SJ Courier Service</span>
        </div>
        <!-- Navigasi di kanan -->
        <div class="flex">
            <!-- Mobile Menu -->
            <div class="dropdown dropdown-end lg:hidden">
                <label tabindex="0" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[100] p-2 shadow bg-white text-black rounded-box w-56">
                    @foreach($links as $link)
                        @if(isset($link['children']))
                            <li>
                                <details>
                                    <summary>{{ $link['label'] }}</summary>
                                    <ul class="p-2">
                                        @foreach($link['children'] as $child)
                                            <li><a href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </details>
                            </li>
                        @else
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endif
                    @endforeach
                    @if($user)
                        <li>
                            <details>
                                <summary>{{ $user->name }}</summary>
                                <ul>
                                    <li>
                                    @if (Auth::user()->role->role_name == 'courier')
                                        <a href="{{ route('courier.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-yellow-100">Profil</a>
                                    @else
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-yellow-100">Profil</a>
                                    @endif
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </details>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endif
                </ul>
            </div>
            <!-- Desktop Menu -->
            <ul class="menu menu-horizontal px-1 hidden lg:flex text-black font-medium z-50">
                @foreach($links as $link)
                    @if(isset($link['children']))
                        <li>
                            <details class="dropdown">
                                <summary class="cursor-pointer px-4 py-2 hover:bg-yellow-300 rounded-md inline-flex items-center gap-1">
                                    {{ $link['label'] }}
                                </summary>
                                <ul class="p-2 bg-white text-black shadow-lg border border-yellow-300 rounded-md w-56 mt-2">
                                    @foreach($link['children'] as $child)
                                        <li><a href="{{ $child['url'] }}" class="hover:bg-yellow-200">{{ $child['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            </details>
                        </li>
                    @else
                        <li><a href="{{ $link['url'] }}" class="px-4 py-2 hover:bg-yellow-300 rounded-md">{{ $link['label'] }}</a></li>
                    @endif
                @endforeach
                @if($user)
                <li>
                    <details class="dropdown">
                        <summary class="cursor-pointer px-4 py-2 hover:bg-yellow-300 rounded-md inline-flex items-center gap-1">
                            {{ $user->name }}
                        </summary>
                        <ul class="p-2 bg-white text-black shadow-lg border border-yellow-300 rounded-md w-40 mt-2">
                            <li>
                            @if (Auth::user()->role->role_name == 'courier')
                                <a href="{{ route('courier.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-yellow-100">Profil</a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-yellow-100">Profil</a>
                            @endif
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-yellow-100"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                                </form>
                            </li>
                        </ul>
                    </details>
                </li>
                @else
                    <li><a href="{{ route('login') }}" class="px-4 py-2 hover:bg-yellow-300 rounded-md">Login</a></li>
                    <li><a href="{{ route('register') }}" class="px-4 py-2 hover:bg-yellow-300 rounded-md">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
