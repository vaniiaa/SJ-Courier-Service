<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6 text-center">Konfirmasi Password</h2>
    <div class="mb-4 text-sm text-gray-600 text-center">
        Area ini aman. Silakan konfirmasi password Anda sebelum melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
            <input id="password" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                type="password" name="password" required autocomplete="current-password">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-2 px-4 rounded-md w-full transition">
            Konfirmasi
        </button>
    </form>
</x-guest-layout>