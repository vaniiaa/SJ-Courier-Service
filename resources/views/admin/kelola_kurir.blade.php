@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Kelola Akun Kurir')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<div class="absolute top-32 left-0 right-0 px-4">

    <div class="max-w-[90rem] mx-auto mb-2 flex justify-end">
        <a href="{{ route('admin.tambah_kurir') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md shadow-gray-700">
            Tambah
        </a>
    </div>

    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded" />
            </form>
        </div>

        <div class="overflow-x-auto border border-gray-300 rounded-lg">
            <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
                <thead class="text-black">
                    <tr class="border border-gray-300">
                        <th class="px-2 py-1">No</th>
                        <th class="text-left px-2 py-1">Nama</th>
                        <th class="text-left px-2 py-1">Email</th>
                        <th class="text-left px-2 py-1">No. HP</th>
                        <th class="text-left px-2 py-1">Alamat</th>
                        <th class="text-left px-2 py-1">User</th>
                        <th class="text-left px-2 py-1">Sandi</th>
                        <th class="px-2 py-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $users = [
                        ['Aulia Sabrina', 'auliasabrina123@gmail.com', '+6281264073088', 'Punggur', 'auliasabrina123', 'aulia321brina'],
                        ['Vania', 'vania61@gmail.com', '+6281264073066', 'Botania', 'vania123', 'vania321!#'],
                        ['Valuhk Januari', 'valuhkjanurat8@gmail.com', '+6281264073055', 'Nongsa', 'valuhk123', 'valuhk534!#'],
                        ['John Doe', 'johndoe166@gmail.com', '+6281264073654', 'Batam Centre', 'johndoe687', 'johndoe687!#'],
                        ['Henry Cavill', 'henrycavill76@gmail.com', '+6281264073076', 'Batu Aji', 'henrycavill076', 'cavillhen076!#'],
                        ['Siti Aminah', 'sitiaminah09@gmail.com', '+6281264073033', 'Tiban', 'aminahsiti', 'aminah321!@#'],
                        ['Bayu Saputra', 'bayusaputra@gmail.com', '+6281264073022', 'Simpang Kabil', 'bayusaputra88', 'saputra#88'],
                        ['Rika Marlina', 'rikamarlina12@gmail.com', '+6281264073011', 'Tanjung Uma', 'rika12', 'rika*12**'],
                        ['David Wijaya', 'davidwijaya@gmail.com', '+6281264073000', 'Baloi', 'davidw123', 'wijaya123!'],
                        ['Nina Agustina', 'ninaagustina@gmail.com', '+6281264073999', 'Sadai', 'nina08', 'ninaAugust#99'],
                    ]; @endphp

                    @foreach ($users as $index => $user)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <td class="px-2 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="px-2 py-1">{{ $user[0] }}</td>
                            <td class="px-2 py-1">{{ $user[1] }}</td>
                            <td class="px-2 py-1">{{ $user[2] }}</td>
                            <td class="px-2 py-1">{{ $user[3] }}</td>
                            <td class="px-2 py-1">{{ $user[4] }}</td>
                            <td class="px-2 py-1">{{ $user[5] }}</td>
                            <td class="px-2 py-1 text-center">
                                <a href="{{ route('admin.edit_kurir') }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 shadow-md shadow-gray-700">Edit</a>
                                <button onclick="bukaModal('{{ $user[0] }}')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-1 shadow-md shadow-gray-700">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-4 space-x-1">
            <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]">&lt;&lt;</button>
            <button class="px-3 py-1 border rounded bg-gradient-to-r from-[#FFA500] to-[#FFD45B]">1</button>
            <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">2</button>
            <button class="px-3 py-1 border rounded bg-white hover:bg-gray-100">...</button>
        </div>
    </div>
</div>

{{-- Modal Popup --}}
<div id="popup-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div id="popup-box" class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-center relative modal-bouncy">
        <button onclick="tutupModal()" class="absolute top-2 right-2 text-red-600 text-xl font-bold hover:text-red-800">&times;</button>
        <div class="text-4xl text-red-600 mb-2">⚠️</div>
        <p class="text-lg font-semibold mb-4">Apakah Anda yakin ingin menghapus akun ini?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="tutupModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Batal</button>
            <button class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded ">Hapus</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/modal.js') }}"></script>
@endsection
