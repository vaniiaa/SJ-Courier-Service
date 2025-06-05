<x-app-layout>
<div class="relative">
    <!-- Background orange hanya di bagian atas -->
    <div class="bg-yellow-400 p-6 shadow-md h-40 w-full absolute top-0 left-0 z-0"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-black mb-8">Data Pengiriman</h1>

        <!-- Isi Data Pengirim -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Isi Data Pengirim</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nama_pengirim" class="block text-sm font-medium text-gray-700">Nama Pengirim</label>
                    <input type="text" id="nama_pengirim" placeholder="Masukkan Nama Anda" class="input input-bordered w-full">
                </div>
                <div>
                    <label for="no_pengirim" class="block text-sm font-medium text-gray-700">No. Handphone</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-yellow-400 text-black rounded-l-md">+62</span>
                        <input type="text" id="no_pengirim" placeholder="Masukkan No HP" class="input input-bordered w-full rounded-l-none">
                    </div>
                </div>
                <div>
                    <label for="email_pengirim" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email_pengirim" placeholder="Masukkan Email Anda" class="input input-bordered w-full">
                </div>
            </div>
            <div class="mt-4">
                <label for="alamat_pengirim" class="block text-sm font-medium text-gray-700">Alamat Penjemputan</label>
                <input type="text" id="alamat_pengirim" placeholder="Masukkan Alamat lengkap" class="input input-bordered w-full">
            </div>
        </div>

        <!-- Isi Data Penerima -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Isi Data Penerima</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama_penerima" class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" id="nama_penerima" placeholder="Masukkan Nama Anda" class="input input-bordered w-full">
                </div>
                <div>
                    <label for="no_penerima" class="block text-sm font-medium text-gray-700">No. Handphone</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-yellow-400 text-black rounded-l-md">+62</span>
                        <input type="text" id="no_penerima" placeholder="Masukkan No HP" class="input input-bordered w-full rounded-l-none">
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <label for="alamat_penerima" class="block text-sm font-medium text-gray-700">Alamat Penerima</label>
                <input type="text" id="alamat_penerima" placeholder="Masukkan Alamat lengkap" class="input input-bordered w-full">
            </div>
        </div>

        <!-- Isi Detail Paket -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Isi Detail Paket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jenis_barang" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                    <select id="jenis_barang" class="select select-bordered w-full">
                        <option disabled selected>Pilih Jenis Barang</option>
                        <option>Baju</option>
                        <option>Dokumen</option>
                        <option>Makanan</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label for="berat_barang" class="block text-sm font-medium text-gray-700">Berat Barang</label>
                    <select id="berat_barang" class="select select-bordered w-full">
                        <option disabled selected>Pilih Berat Barang</option>
                        <option>Kecil (1 - 5 Kg)</option>
                        <option>Besar (6 - 10 Kg)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol -->
        <div class="text-center mt-8">
            <button class="btn bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-lg shadow-md">Lanjutkan</button>
        </div>
    </div>
</div>
    </x-app-layout>