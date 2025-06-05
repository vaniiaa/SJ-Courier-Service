@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Kelola Akun Kurir')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">

<div class="absolute top-28 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto mb-2 flex justify-end">
        <a href="{{ route('admin.tambah_kurir') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md shadow-gray-700">
            Tambah Kurir
        </a>
    </div>

    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-4 py-2 rounded" />
            </form>
        </div>

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
                <button onclick="tutupModal()" class="px-3 py-1.5 rounded bg-gray-300 hover:bg-gray-400 transition text-xs">Batal</button>
                <button id="confirmDeleteBtn" class="px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700 transition text-xs">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Modal --}}
<script>
let formToDeleteId = null;

function bukaModal(id, name) {
    formToDeleteId = id;
    document.getElementById('modal-item-name').textContent = name; // Changed from ${name} to name

    const modal = document.getElementById('popup-modal');
    modal.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
    modal.classList.add('opacity-100');

    document.querySelector('body').classList.add('modal-open');
}

function tutupModal() {
    const modal = document.getElementById('popup-modal');
    const modalContent = modal.querySelector('div > div');

    modalContent.classList.add('scale-90');
    modalContent.classList.remove('scale-100');

    setTimeout(() => {
        modal.classList.add('opacity-0', 'pointer-events-none', 'hidden');
        modal.classList.remove('opacity-100');
        formToDeleteId = null;
        document.querySelector('body').classList.remove('modal-open');
    }, 300);
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (formToDeleteId) {
        const form = document.getElementById('hapusForm-' + formToDeleteId);
        if (form) {
            form.submit();
        }
    }
});
</script>

<style>
/* CSS Tambahan untuk menggelapkan header saat modal terbuka */
.modal-open .admin-header {
    filter: brightness(0.5); /* Menggelapkan sebesar 50% */
    transition: filter 0.3s ease-in-out;
    pointer-events: none; /* Menonaktifkan interaksi klik */
}
</style>
@endsection