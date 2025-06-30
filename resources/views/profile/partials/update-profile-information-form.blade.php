<div class="bg-yellow-400 p-6 shadow-md h-40 w-full absolute top-0 left-0 z-0"></div>
 
    <section class="max-w-[90rem] mx-auto relative z-40 -mt-[190px]">
        <div class="bg-white rounded-lg shadow-lg p-6"> {{-- Ini adalah "kartu besar" putih yang membungkus semua --}}

    {{-- Bagian Informasi Profil Utama --}}
    <div class="flex flex-col md:flex-row gap-6 items-center w-full">
        <div class="flex justify-center items-center w-40 h-40 bg-gray-100 rounded-full flex-shrink-0 md:ml-10">
            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 gap-x-10 w-full px-0 md:px-6">
            {{-- Nama --}}
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium text-gray-700">Nama :</label>
                <div class="relative flex-1">
                    <input
                        type="text"
                        value="{{ $user->name }}"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none cursor-default"
                        readonly
                        id="user-name-display"
                    />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer"
                            onclick="openModal('name', '{{ $user->name }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Phone --}}
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium text-gray-700">Phone :</label>
                <div class="relative flex-1">
                    <input
                        type="text"
                        value="{{ $user->phone ?? '-' }}"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none cursor-default"
                        readonly
                        id="user-phone-display"
                    />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer"
                            onclick="openModal('phone', '{{ $user->phone ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium text-gray-700">Email :</label>
                <div class="relative flex-1">
                    <input
                        type="email"
                        value="{{ $user->email }}"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none cursor-default"
                        readonly
                        id="user-email-display"
                    />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer"
                            onclick="openModal('email', '{{ $user->email }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium text-gray-700">Address :</label>
                <div class="relative flex-1">
                    <input
                        type="text"
                        value="{{ $user->address ?? '-' }}"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none cursor-default"
                        readonly
                        id="user-address-display"
                    />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer"
                            onclick="openModal('address', '{{ $user->address ?? '' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kata Sandi --}}
            <div class="flex items-center gap-1">
                <label class="w-28 text-sm font-medium text-gray-700">Kata Sandi :</label>
                <div class="relative flex-1">
                    <input
                        type="password"
                        value="*********"
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none cursor-default"
                        readonly
                    />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 cursor-pointer"
                            onclick="openModal('password', '')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Status Verifikasi Email (Jika perlu) --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="md:col-span-2 mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center justify-between text-sm text-yellow-800">
                    <span>
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                    </span>
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="ml-4 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition duration-200">
                            {{ __('Kirim Ulang Verifikasi') }}
                        </button>
                    </form>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <div class="md:col-span-2 mt-2 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    ---

    <div class="flex justify-end mt-4">
        <button type="button"
                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200"
                onclick="openDeleteModal()">
            Hapus Akun
        </button>
    </div>

    {{-- Pesan Status Global --}}
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated' || session('status') === 'account-deleted')
        <div id="status-message"
            class="fixed bottom-4 right-4 p-3 bg-green-500 text-white rounded-md shadow-lg text-sm font-medium z-50">
            @if (session('status') === 'profile-updated')
                {{ __('Profil berhasil diperbarui.') }}
            @elseif (session('status') === 'password-updated')
                {{ __('Kata sandi berhasil diperbarui.') }}
            @elseif (session('status') === 'account-deleted')
                {{ __('Akun berhasil dihapus.') }}
            @endif
        </div>
        <script>
            setTimeout(() => {
                const statusMessage = document.getElementById('status-message');
                if (statusMessage) {
                    statusMessage.classList.add('hidden');
                }
            }, 2000);
        </script>
    @endif

</section>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-start z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl mx-auto" style="margin-top: 8vh;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold" id="modalTitle">Edit Data</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <input type="hidden" id="modalFieldName" name="field">

            <div class="mb-4">
                <label for="modalFieldValue" class="block text-sm font-medium text-gray-700" id="modalLabel">Nilai Baru</label>
                <input type="text" id="modalFieldValue" name="value"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                
                {{-- *** TAMBAHKAN BAGIAN INI UNTUK MENAMPILKAN ERROR VALIDASI *** --}}
                @error('value')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                {{-- ********************************************************** --}}
            </div>

            {{-- Tambahan untuk Konfirmasi Password (akan ditampilkan hanya untuk password) --}}
            <div id="passwordConfirmationField" class="mb-4 hidden">
                <label for="modalFieldValueConfirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="modalFieldValueConfirmation" name="value_confirmation"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                
                {{-- *** TAMBAHKAN BAGIAN INI UNTUK MENAMPILKAN ERROR VALIDASI KONFIRMASI PASSWORD *** --}}
                @error('value_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                {{-- ******************************************************************************** --}}
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                        onclick="closeModal()">Batal</button>
                <button type="submit"
                        style="padding: 10px 20px;
                               background-color: #facc15;
                               color: black;
                               border: 1px solid #eab308;
                               border-radius: 6px;
                               font-weight: bold;
                               cursor: pointer;
                               box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                               transition: background-color 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#eab308'"
                        onmouseout="this.style.backgroundColor='#facc15'">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-start z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl mx-auto" style="margin-top: 8vh;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-red-600">Konfirmasi Penghapusan Akun</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeDeleteModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus akun Anda secara permanen? Tindakan ini tidak dapat dibatalkan.</p>

        {{-- FORM HAPUS AKUN DENGAN INPUT PASSWORD --}}
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="mb-4">
                <label for="deleteAccountPassword" class="block text-sm font-medium text-gray-700">Masukkan Kata Sandi Anda untuk Konfirmasi</label>
                <input type="password" id="deleteAccountPassword" name="password" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                        onclick="closeDeleteModal()">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    // Fungsi yang sudah ada untuk modal edit
    function openModal(fieldName, currentValue) {
        const modal = document.getElementById('editModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalLabel = document.getElementById('modalLabel');
        const modalFieldName = document.getElementById('modalFieldName');
        const modalFieldValue = document.getElementById('modalFieldValue');
        const editForm = document.getElementById('editForm');
        const passwordConfirmationField = document.getElementById('passwordConfirmationField'); // Ambil elemen konfirmasi password
        const modalFieldValueConfirmation = document.getElementById('modalFieldValueConfirmation'); // Input konfirmasi

        modalTitle.textContent = 'Edit ' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
        modalLabel.textContent = 'Masukkan ' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + ' Baru:';
        modalFieldName.value = fieldName;

        // Atur tipe input berdasarkan field
        if (fieldName === 'email') {
            modalFieldValue.type = 'email';
            modalFieldValue.value = currentValue;
            passwordConfirmationField.classList.add('hidden'); // Sembunyikan konfirmasi password
        } else if (fieldName === 'password') {
            modalFieldValue.type = 'password';
            modalFieldValue.value = ''; // Kosongkan untuk input password
            passwordConfirmationField.classList.remove('hidden'); // Tampilkan field konfirmasi
            modalFieldValueConfirmation.value = ''; // Kosongkan juga
            modalFieldValue.setAttribute('autocomplete', 'new-password'); // Saran keamanan
            modalFieldValueConfirmation.setAttribute('autocomplete', 'new-password'); // Saran keamanan
        } else if (fieldName === 'phone') {
            modalFieldValue.type = 'tel'; // Tipe input tel untuk nomor telepon
            modalFieldValue.value = currentValue;
            passwordConfirmationField.classList.add('hidden'); // Sembunyikan konfirmasi password
        } else {
            modalFieldValue.type = 'text';
            modalFieldValue.value = currentValue;
            passwordConfirmationField.classList.add('hidden'); // Sembunyikan konfirmasi password
        }

        // --- PENTING: SESUAIKAN DENGAN RUTE BARU YANG BENAR ---
        editForm.action = '/profile/update/' + fieldName; // Ini akan menghasilkan /profile/update/name, /profile/update/email, dll.

        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        const passwordConfirmationField = document.getElementById('passwordConfirmationField');
        passwordConfirmationField.classList.add('hidden'); // Pastikan tersembunyi saat modal ditutup
        modal.classList.add('hidden');
    }

    // Menutup modal jika klik di luar area modal (backdrop)
    document.getElementById('editModal').addEventListener('click', function(event) {
        // Hanya tutup modal jika yang diklik adalah div modal itu sendiri (backdrop), bukan konten dalamnya
        if (event.target === this) {
            closeModal();
        }
    });

    // Menutup modal dengan tombol ESC
    document.addEventListener('keydown', function(event) {
        // Pastikan hanya satu modal yang aktif yang merespons tombol ESC
        if (event.key === 'Escape') {
            const editModal = document.getElementById('editModal');
            const deleteAccountModal = document.getElementById('deleteAccountModal');

            if (!editModal.classList.contains('hidden')) {
                closeModal();
            } else if (!deleteAccountModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });

    // Fungsi untuk modal Hapus Akun
    const deleteAccountModal = document.getElementById('deleteAccountModal');

    function openDeleteModal() {
        deleteAccountModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        // Kosongkan input password saat modal ditutup
        document.getElementById('deleteAccountPassword').value = '';
        deleteAccountModal.classList.add('hidden');
    }

    // Menutup modal hapus jika klik di luar area modal (backdrop)
    deleteAccountModal.addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    // Pesan Status Global (kode yang sudah ada)
    setTimeout(() => {
        const statusMessage = document.getElementById('status-message');
        if (statusMessage) {
            statusMessage.classList.add('hidden');
        }
    }, 2000);
</script>