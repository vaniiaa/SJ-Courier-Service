<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
            <input id="email" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
            <input id="password" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                type="password" name="password" required autocomplete="new-password">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-2 px-4 rounded-md w-full transition">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>