<x-guest-layout>
    <h2 class="text-2xl font-bold mb-6 text-center">Verifikasi Email</h2>
    <div class="mb-4 text-sm text-gray-600 text-center">
        Terima kasih telah mendaftar! Sebelum mulai, silakan verifikasi email Anda dengan mengklik link yang telah kami kirim ke email Anda.<br>
        Jika belum menerima email, kami dapat mengirim ulang.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 text-center">
            Link verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-2 items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-2 px-4 rounded-md transition w-full">
                Kirim Ulang Email Verifikasi
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 w-full">
                Logout
            </button>
        </form>
    </div>
</x-guest-layout>