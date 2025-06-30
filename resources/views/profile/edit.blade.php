<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

  <div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <p>Konten atau informasi lainnya bisa ditaruh di sini...</p>
    </div>

    {{-- Tambahkan div kosong sebagai pemisah --}}
    <div style="height: 4rem;"></div> {{-- Anda bisa menggunakan Tailwind h-16 di sini juga --}}

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('profile.partials.update-profile-information-form')
    </div>
</div>
</x-app-layout>