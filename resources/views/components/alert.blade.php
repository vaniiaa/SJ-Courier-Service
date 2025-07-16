<div {{ $attributes->merge(['class' => 'alert ' . $alertClasses(), 'role' => 'alert']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
        {{-- Ikon SVG akan dirender di sini secara dinamis --}}
        {!! $iconPath() !!}
    </svg>

    {{-- $slot adalah tempat untuk pesan kustom Anda --}}
    <span>{{ $slot }}</span>
</div>  