@props(['menus' => [], 'year' => null])

<footer class="bg-gradient-to-r from-[#FFA500] to-[#FFD45B] text-black mt-16">
    <div class="max-w-screen-xl mx-auto py-10 px-4 flex flex-col md:flex-row justify-between space-y-8 md:space-y-0">
        <!-- Logo & Deskripsi -->
        <div>
            <img src="{{ asset('images/admin/logo.png') }}" alt="SJ Courier" class="h-10 mb-4">
            <p class="text-sm">
                SJ City Courier adalah layanan pengiriman cepat, aman, dan terpercaya. Dengan sistem terintegrasi, kurir dapat memantau, memperbarui status, dan mengonfirmasi pengiriman secara real-time untuk memastikan layanan yang efisien dan transparan.
            </p>
        </div>
        <!-- Menu Dinamis -->
        @foreach($menus as $menu)
            <div>
                <h3 class="font-bold mb-2">{{ $menu['title'] }}</h3>
                @if(isset($menu['items']) && is_array($menu['items']))
                    <ul class="space-y-1 text-sm">
                        @foreach($menu['items'] as $item)
                            <li>
                                <a href="{{ $item['url'] ?? '#' }}" class="hover:underline">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @elseif(isset($menu['content']))
                    <div class="text-sm">{!! $menu['content'] !!}</div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="text-center text-sm text-black mt-8">
        &copy; {{ $year ?? date('Y') }} All Rights Reserved - SJ Courier Service
    </div>
</footer>