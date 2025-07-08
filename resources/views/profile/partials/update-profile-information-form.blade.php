<div class="bg-[rgba(255,165,0,0.75)] p-6 shadow-md h-40 w-full" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);">
    <div class="max-w-[90rem] mx-auto px-6"> <!-- Tambahkan pembungkus agar sejajar -->
        <h1 class="text-2xl font-bold text-black">Profile</h1>
    </div>
</div>

<section class="max-w-[90rem] mx-auto relative px-4 z-10" style="margin-top: -4rem;">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-20">
        {{-- Ini adalah "kartu besar" putih yang membungkus semua --}}

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
                    {{-- Notifikasi untuk Nama --}}
                    <div id="status-name" class="status-message text-sm mt-1"></div>
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
                    {{-- Notifikasi untuk Phone --}}
                    <div id="status-phone" class="status-message text-sm mt-1"></div>
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
                    {{-- Notifikasi untuk Email --}}
                    <div id="status-email" class="status-message text-sm mt-1"></div>
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
                    {{-- Notifikasi untuk Address --}}
                    <div id="status-address" class="status-message text-sm mt-1"></div>
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
                    {{-- Notifikasi untuk Kata Sandi --}}
                    <div id="status-password" class="status-message text-sm mt-1"></div>
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
                    {{-- Notifikasi Verifikasi Email (Menggunakan CSS Inline) --}}
                    <div class="md:col-span-2 mt-2 p-3 rounded-lg text-sm" style="background-color: #e6ffe6; border: 1px solid #cceccc; color: #338833;">
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </div>
                @endif
            @endif

        </div>
    </div>


    <div class="flex justify-end mt-4">
        <button type="button"
                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200"
                onclick="openDeleteModal()">
            Hapus Akun
        </button>
    </div>

    </div>
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
                {{-- Label ini akan diatur secara dinamis oleh JavaScript --}}
                <label for="modalFieldValue" class="block text-sm font-medium text-gray-700" id="modalLabel">Isian</label>
                <input type="text" id="modalFieldValue" name="value"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                
                {{-- Notifikasi Error untuk Input di Modal Edit --}}
                <div id="modal-status-value" class="status-message text-sm mt-1"></div>
            </div>

            {{-- Tambahan untuk Konfirmasi Password (akan ditampilkan hanya untuk password) --}}
            <div id="passwordConfirmationField" class="mb-4 hidden">
                <label for="modalFieldValueConfirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="modalFieldValueConfirmation" name="value_confirmation"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                
                {{-- Notifikasi Error untuk Konfirmasi Kata Sandi di Modal Edit --}}
                <div id="modal-status-value_confirmation" class="status-message text-sm mt-1"></div>
            </div>

            {{-- Input Kata Sandi Saat Ini (Hanya untuk perubahan password) --}}
            <div id="currentPasswordField" class="mb-4 hidden">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Kata Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                <div id="modal-status-current_password" class="status-message text-sm mt-1"></div>
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
                {{-- Notifikasi Error untuk Kata Sandi Hapus Akun --}}
                <div id="status-deleteAccountPassword" class="status-message text-sm mt-1"></div>
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
    // Fungsi untuk menampilkan pesan status di bawah kolom yang diedit atau modal
    function showStatusMessage(elementId, message, type = 'success') {
        const statusElement = document.getElementById(elementId);
        if (statusElement) {
            statusElement.textContent = '';
            statusElement.classList.add('p-2', 'rounded-md'); // Tetap pakai Tailwind untuk padding & radius

            if (type === 'success') {
                statusElement.style.backgroundColor = '#e6ffe6'; // Hijau soft
                statusElement.style.borderColor = '#cceccc';    // Border hijau soft
                statusElement.style.color = '#338833';          // Teks hijau agak gelap
                statusElement.style.borderWidth = '1px';
                statusElement.style.borderStyle = 'solid';
            } else { // Jika type adalah 'error'
                statusElement.style.backgroundColor = '#ffe6e6'; // Merah soft
                statusElement.style.borderColor = '#ffcccc';    // Border merah soft
                statusElement.style.color = '#cc3333';          // Teks merah agak gelap
                statusElement.style.borderWidth = '1px';
                statusElement.style.borderStyle = 'solid';
            }
            statusElement.textContent = message;

            // Sembunyikan pesan setelah beberapa detik
            setTimeout(() => {
                statusElement.textContent = '';
                statusElement.removeAttribute('style');
                statusElement.classList.remove('p-2', 'rounded-md');
                statusElement.className = 'status-message text-sm mt-1'; // Reset ke kelas dasar
            }, 3000);
        }
    }

    // Fungsi yang sudah ada untuk modal edit
    function openModal(fieldName, currentValue) {
        // Hapus semua pesan status yang mungkin ada sebelum membuka modal baru
        document.querySelectorAll('.status-message').forEach(el => {
            el.textContent = '';
            el.removeAttribute('style');
            el.classList.remove('p-2', 'rounded-md');
            el.className = 'status-message text-sm mt-1';
        });

        const modal = document.getElementById('editModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalLabel = document.getElementById('modalLabel');
        const modalFieldName = document.getElementById('modalFieldName');
        const modalFieldValue = document.getElementById('modalFieldValue');
        const editForm = document.getElementById('editForm');
        const passwordConfirmationField = document.getElementById('passwordConfirmationField');
        const modalFieldValueConfirmation = document.getElementById('modalFieldValueConfirmation');
        const currentPasswordField = document.getElementById('currentPasswordField');
        const currentPasswordInput = document.getElementById('current_password');

        modalTitle.textContent = 'Edit ' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
        modalLabel.textContent = fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + ':';
        modalFieldName.value = fieldName;

        // Reset display
        passwordConfirmationField.classList.add('hidden');
        currentPasswordField.classList.add('hidden');
        modalFieldValue.type = 'text'; // Default to text

        if (fieldName === 'email') {
            modalFieldValue.type = 'email';
            modalFieldValue.value = currentValue;
        } else if (fieldName === 'password') {
            modalFieldValue.type = 'password';
            modalFieldValue.value = '';
            passwordConfirmationField.classList.remove('hidden');
            modalFieldValueConfirmation.value = '';
            modalFieldValue.setAttribute('autocomplete', 'new-password');
            modalFieldValueConfirmation.setAttribute('autocomplete', 'new-password');
            currentPasswordField.classList.remove('hidden'); // Tampilkan hanya untuk perubahan password
            currentPasswordInput.value = '';
        } else if (fieldName === 'phone') {
            modalFieldValue.type = 'tel';
            modalFieldValue.value = currentValue;
        } else if (fieldName === 'address') {
            modalFieldValue.type = 'text';
            modalFieldValue.value = currentValue;
        } else { // For Name, etc.
            modalFieldValue.type = 'text';
            modalFieldValue.value = currentValue;
        }

        editForm.action = '/profile/update/' + fieldName; 

        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('editModal');
        // Clear inputs and hide fields
        document.getElementById('modalFieldValue').value = '';
        document.getElementById('modalFieldValueConfirmation').value = '';
        document.getElementById('current_password').value = '';
        document.getElementById('passwordConfirmationField').classList.add('hidden');
        document.getElementById('currentPasswordField').classList.add('hidden');
        
        // Clear any error messages from the modal
        document.querySelectorAll('#editModal .status-message').forEach(el => {
            el.textContent = '';
            el.removeAttribute('style');
            el.classList.remove('p-2', 'rounded-md');
            el.className = 'status-message text-sm mt-1';
        });

        modal.classList.add('hidden');
    }

    document.getElementById('editModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
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

    const deleteAccountModal = document.getElementById('deleteAccountModal');

    function openDeleteModal() {
        // Clear any previous error messages
        document.getElementById('status-deleteAccountPassword').textContent = '';
        document.getElementById('status-deleteAccountPassword').removeAttribute('style');
        document.getElementById('status-deleteAccountPassword').classList.remove('p-2', 'rounded-md');
        document.getElementById('status-deleteAccountPassword').className = 'status-message text-sm mt-1';

        deleteAccountModal.classList.remove('hidden');
        // Fokuskan pada input password saat modal dibuka
        document.getElementById('deleteAccountPassword').focus();
    }

    function closeDeleteModal() {
        document.getElementById('deleteAccountPassword').value = '';
        // Clear any error messages when closing
        document.getElementById('status-deleteAccountPassword').textContent = '';
        document.getElementById('status-deleteAccountPassword').removeAttribute('style');
        document.getElementById('status-deleteAccountPassword').classList.remove('p-2', 'rounded-md');
        document.getElementById('status-deleteAccountPassword').className = 'status-message text-sm mt-1';

        deleteAccountModal.classList.add('hidden');
    }

    deleteAccountModal.addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    // Menangani status setelah permintaan pembaruan berhasil (dari Laravel)
    @if (session('status'))
        @php
            $status = session('status');
            $message = '';
            $type = 'success'; // Default type
            $elementId = ''; // Element ID to show message

            if ($status === 'profile-updated') {
                $message = __('Profil berhasil diperbarui.');
                $fieldName = session('field') ?? '';
                $elementId = 'status-' . $fieldName;
            } elseif ($status === 'password-updated') {
                $message = __('Kata sandi berhasil diperbarui.');
                $elementId = 'status-password';
            } elseif ($status === 'account-deleted') {
                $message = __('Akun berhasil dihapus.');
                // For account deletion, you might redirect or show a global alert
            }
        @endphp

        document.addEventListener('DOMContentLoaded', function() {
            @if ($message && $elementId && $status !== 'account-deleted')
                showStatusMessage('{{ $elementId }}', '{{ $message }}', '{{ $type }}');
            @elseif ($status === 'account-deleted')
                alert('{{ $message }}');
                // window.location.href = '/login'; // Redirect if needed
            @endif
        });
    @endif

    // Menangani kesalahan validasi dari Laravel
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada error terkait penghapusan akun
            @if ($errors->has('password') && session('from_delete_modal'))
                openDeleteModal(); // Buka kembali modal hapus akun
                showStatusMessage('status-deleteAccountPassword', '{{ $errors->first('password') }}', 'error');
                document.getElementById('deleteAccountPassword').classList.add('border-red-500'); // Tambahkan border merah pada input
                document.getElementById('deleteAccountPassword').focus(); // Fokuskan kembali input
            @else
                // Jika error berasal dari modal edit, kita TIDAK AKAN MEMBUKA MODAL SECARA PAKSA.
                // Kita hanya akan mencoba menampilkan pesan error JIKA modal sudah terbuka.
                const modal = document.getElementById('editModal');
                const fieldName = document.getElementById('modalFieldName').value;

                // Hanya proses error jika modal terlihat (tidak hidden) dan fieldName di set.
                // Ini akan mencegah modal muncul secara otomatis karena error validasi.
                if (!modal.classList.contains('hidden') && fieldName) { 
                    if (fieldName === 'password') {
                        document.getElementById('passwordConfirmationField').classList.remove('hidden');
                        document.getElementById('currentPasswordField').classList.remove('hidden');

                        // Jika ada error pada 'current_password', tampilkan di bawah input tersebut
                        @if ($errors->has('current_password'))
                            showStatusMessage('modal-status-current_password', '{{ $errors->first('current_password') }}', 'error');
                            document.getElementById('current_password').classList.add('border-red-500');
                            // Tidak perlu fokuskan input jika user tidak ingin pop-up
                        @endif
                        // Jika ada error pada 'value' (sandi baru) atau 'value_confirmation' (konfirmasi sandi baru)
                        @if ($errors->has('value'))
                            showStatusMessage('modal-status-value', '{{ $errors->first('value') }}', 'error');
                            document.getElementById('modalFieldValue').classList.add('border-red-500');
                        @endif
                        @if ($errors->has('value_confirmation'))
                            showStatusMessage('modal-status-value_confirmation', '{{ $errors->first('value_confirmation') }}', 'error');
                            document.getElementById('modalFieldValueConfirmation').classList.add('border-red-500');
                        @endif
                    } else {
                        // Untuk nama, email, phone, address, SEMBUNYIKAN current password field
                        document.getElementById('passwordConfirmationField').classList.add('hidden');
                        document.getElementById('currentPasswordField').classList.add('hidden');

                        // Tampilkan error validasi di bawah input terkait di dalam modal edit
                        @if ($errors->has('value'))
                            showStatusMessage('modal-status-value', 'Input tidak valid.', 'error');
                            document.getElementById('modalFieldValue').classList.add('border-red-500');
                        @endif
                    }
                }
            @endif
        });
    @endif
</script>