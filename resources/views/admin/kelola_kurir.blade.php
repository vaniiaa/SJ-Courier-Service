@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Kelola Akun Kurir')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">

{{-- CSS Tambahan untuk Transisi Notifikasi & Gelap Header Modal --}}
<style>
    /* Style untuk transisi muncul dan menghilang pada notifikasi */
    .notification-transition {
        opacity: 0;
        transform: translateY(-20px); /* Mulai sedikit di atas */
        transition: opacity 0.5s ease-out, transform 0.5s ease-out; /* Transisi 0.5 detik */
    }

    .notification-transition.show {
        opacity: 1;
        transform: translateY(0); /* Kembali ke posisi normal */
    }

    /* CSS untuk menggelapkan header saat modal terbuka */
    .modal-open .admin-header {
        filter: brightness(0.5); /* Menggelapkan sebesar 50% */
        transition: filter 0.3s ease-in-out;
        pointer-events: none; /* Menonaktifkan interaksi klik pada header */
    }
</style>

<div class="absolute top-28 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto mb-2 flex justify-end">
        <a href="{{ route('admin.tambah_kurir') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md shadow-gray-700">
            Tambah Kurir
        </a>
    </div>

    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar (MODIFIED) --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('admin.kelola_kurir') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div id="success-alert" class="notification-transition" role="alert"
                 style="background-color: #d1fae5; /* Warna hijau muda */
                        border: 1px solid #34d399; /* Border hijau */
                        color: #047857; /* Warna teks hijau gelap */
                        padding: 1rem; /* Padding internal */
                        border-radius: 0.5rem; /* Sudut membulat */
                        margin-bottom: 1rem; /* Margin bawah */
                        position: relative;">
                <strong style="font-weight: bold;">Berhasil!</strong>
                <span style="display: inline;">{{ session('success') }}</span>
                <span style="position: absolute; top: 0; bottom: 0; right: 0; padding: 1rem; cursor: pointer;" onclick="document.getElementById('success-alert').style.display='none'">
                    <svg style="height: 1.5rem; width: 1.5rem; fill: currentColor; color: #10b981;" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.651a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.181l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.697z"/>
                    </svg>
                </span>
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div id="error-alert" class="notification-transition" role="alert"
                 style="background-color: #fee2e2; /* Warna merah muda */
                        border: 1px solid #f87171; /* Border merah */
                        color: #b91c1c; /* Warna teks merah gelap */
                        padding: 1rem; /* Padding internal */
                        border-radius: 0.5rem; /* Sudut membulat */
                        margin-bottom: 1rem; /* Margin bawah */
                        position: relative;">
                <strong style="font-weight: bold;">Gagal!</strong>
                <span style="display: inline;">{{ session('error') }}</span>
                <span style="position: absolute; top: 0; bottom: 0; right: 0; padding: 1rem; cursor: pointer;" onclick="document.getElementById('error-alert').style.display='none'">
                    <svg class="fill-current" style="height: 1.5rem; width: 1.5rem; color: #ef4444;" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 2.651a1.2 1.2 0 1 1-1.697-1.697L8.303 10l-2.651-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.181l2.651-2.651a1.2 1.2 0 1 1 1.697 1.697L11.697 10l2.651 2.651a1.2 1.2 0 0 1 0 1.697z"/>
                    </svg>
                </span>
            </div>
        @endif
        
        <div class="overflow-x-auto border border-gray-300 rounded-lg">
            <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
                <thead class="text-black">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2">No</th>
                        <th class="text-left px-4 py-2">Nama</th>
                        <th class="text-left px-4 py-2">Email</th>
                        <th class="text-left px-4 py-2">No. HP</th>
                        <th class="text-left px-4 py-2">Alamat</th>
                        <th class="text-left px-4 py-2">Wilayah Pengiriman</th>
                        <th class="text-left px-4 py-2">Username</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kurirs as $index => $kurir)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $kurir->nama }}</td>
                            <td class="px-4 py-2">{{ $kurir->email }}</td>
                            <td class="px-4 py-2">{{ $kurir->no_hp }}</td>
                            <td class="px-4 py-2">{{ $kurir->alamat }}</td>
                            <td class="px-4 py-2">{{ $kurir->wilayah_pengiriman }}</td>
                            <td class="px-4 py-2">{{ $kurir->username }}</td>
                            <td class="px-4 py-2 text-center">
                                <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; height: 100%;">
                                    <a href="{{ route('admin.edit_kurir', $kurir->id) }}"
                                       style="background-color: #22c55e; color: white; padding: 0.3rem 0.75rem; border-radius: 0.375rem;
                                              box-shadow: 0 2px 6px rgba(0,0,0,0.15); text-decoration: none; font-size: 0.875rem;
                                              transition: background-color 0.3s ease;"
                                       onmouseover="this.style.backgroundColor='#16a34a'"
                                       onmouseout="this.style.backgroundColor='#22c55e'">
                                        Edit
                                    </a>
                                    <form id="hapusForm-{{ $kurir->id }}" action="{{ route('admin.hapus_kurir', $kurir->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="bukaModal('{{ $kurir->id }}', '{{ addslashes($kurir->nama) }}')"
                                                style="background-color: #ef4444; color: white; padding: 0.3rem 0.75rem; border-radius: 0.375rem;
                                                       box-shadow: 0 2px 6px rgba(0,0,0,0.15); font-size: 0.875rem; border: none; cursor: pointer;
                                                       transition: background-color 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#ef4444'">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Data kurir tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="flex justify-end mt-4">
    {{ $kurirs->links() }}
</div>

{{-- Modal Popup --}}
<div id="popup-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full p-4 transform scale-90 transition-transform duration-300 relative" style="max-width: 400px;">
        <button onclick="tutupModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
        <div class="flex flex-col items-center space-y-3">
            <svg class="w-10 h-10 text-red-600 animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900 text-center">
                Yakin ingin menghapus <span id="modal-item-name" class="font-bold text-red-600"></span>?
            </h3>
            <div class="flex space-x-3 pt-1">
                <button onclick="tutupModal()" class="px-3 py-2 rounded bg-gray-300 hover:bg-gray-400 transition text-xs">Batal</button>
                <button id="confirmDeleteBtn" class="px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition text-xs">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/kurir.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        // Fungsi untuk menampilkan notifikasi dengan transisi
        function showNotification(alertElement) {
            if (alertElement) {
                // Pastikan elemen terlihat (display: block) sebelum menambahkan kelas 'show'
                // Ini penting agar transisi 'opacity' dan 'transform' bisa berjalan mulus dari awal.
                alertElement.style.display = 'block'; 
                setTimeout(() => {
                    alertElement.classList.add('show');
                }, 100); // Memberi sedikit delay (100ms) untuk memastikan browser merender status awal sebelum transisi
            }
        }

        // Fungsi untuk menyembunyikan notifikasi dengan transisi
        function hideNotification(alertElement) {
            if (alertElement) {
                alertElement.classList.remove('show'); // Hapus kelas 'show' untuk memicu transisi menghilang
                // Setelah transisi selesai, sembunyikan elemen sepenuhnya (display: none)
                alertElement.addEventListener('transitionend', function() {
                    alertElement.style.display = 'none';
                }, { once: true }); // Listener ini hanya akan berjalan sekali
            }
        }

        // Jalankan logika untuk notifikasi sukses
        if (successAlert) {
            showNotification(successAlert); // Tampilkan notifikasi
            setTimeout(() => {
                hideNotification(successAlert); // Sembunyikan setelah 5 detik
            }, 5000); // 5000 milidetik = 5 detik
        }
        
        // Jalankan logika untuk notifikasi error
        if (errorAlert) {
            showNotification(errorAlert); // Tampilkan notifikasi
            setTimeout(() => {
                hideNotification(errorAlert); // Sembunyikan setelah 5 detik
            }, 5000); // 5000 milidetik = 5 detik
        }
    });
</script>
@endsection