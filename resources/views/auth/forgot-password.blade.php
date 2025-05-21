<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6 text-center">Lupa Password</h2>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan email Anda, kami akan mengirimkan link untuk reset password.
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
            <input id="email" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-2 px-4 rounded-md w-full transition">
            Kirim Link Reset Password
        </button>
    </form>
</x-guest-layout>