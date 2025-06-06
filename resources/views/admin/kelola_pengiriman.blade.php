@extends('layouts.admin')

{{-- Pastikan file layouts/admin.blade.php Anda memiliki meta tag CSRF token di dalam bagian <head>:
<meta name="csrf-token" content="{{ csrf_token() }}">
Ini penting untuk keamanan Laravel, terutama saat mengirimkan data via AJAX. --}}

{{-- Asumsi Anda memiliki komponen sidebar. Sesuaikan path jika berbeda. --}}
@include('components.admin.sidebar')

@section('title', 'Kelola Pengiriman')

@section('content')
<div class="absolute top-36 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-4">
        {{-- Search Bar --}}
        <div class="flex justify-end items-center mb-4">
            <form action="{{ route('admin.kelola_pengiriman') }}" method="GET" class="flex items-center gap-2">
                <label for="search" class="font-medium text-sm">Search:</label>
                <input type="text" id="search" name="search" placeholder="Cari resi / nama" class="border px-2 py-1 rounded text-sm" value="{{ request('search') }}" />
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Cari</button>
            </form>
        </div>

        {{-- Tabel Data Pengiriman --}}
        <div class="overflow-x-auto border border-gray-300 rounded-lg mt-[-10px]">
            <table class="w-full table-auto text-sm rounded-lg overflow-hidden">
                <thead class="bg-gray-50 text-gray-700 text-sm">
                    <tr class="border border-gray-300">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-center">Resi</th>
                        <th class="px-4 py-2 text-left">Nama Pengirim</th>
                        <th class="px-4 py-2 text-left">Alamat Penjemputan</th>
                        <th class="px-4 py-2 text-left">Nama Penerima</th>
                        <th class="px-4 py-2 text-left">Alamat Tujuan</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-center">Berat (kg)</th>
                        <th class="px-4 py-2 text-center">Harga (Rp)</th>
                        <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                        <th class="px-4 py-2 text-left">Kurir</th>
                        <th class="px-4 py-2 text-center">Status Pengiriman</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengiriman as $index => $data)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}" id="row-{{ $data->id }}">
                            <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->resi }}</td>
                            <td class="px-4 py-2">{{ $data->nama_pengirim }}</td>
                            <td class="px-4 py-2">{{ $data->alamat_penjemputan }}</td>
                            <td class="px-4 py-2">{{ $data->nama_penerima }}</td>
                            <td class="px-4 py-2">{{ $data->alamat_tujuan }}</td>
                            {{-- Format tanggal pemesanan --}}
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->tanggal_pemesanan)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->berat }}</td>
                            {{-- Format harga dengan pemisah ribuan --}}
                            <td class="px-4 py-2 text-center">{{ number_format($data->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->metode_pembayaran }}</td>
                            {{-- Menampilkan nama kurir dari kolom `nama_kurir` di tabel `pengiriman` --}}
                            <td class="px-4 py-2 text-center">{{ $data->nama_kurir ?? 'Belum Ditentukan' }}</td>
                            {{-- Menampilkan status pengiriman dengan warna berdasarkan status --}}
                            <td class="px-4 py-2 text-center font-semibold text-sm
                                @if ($data->status_pengiriman === 'menunggu konfirmasi') text-gray-600
                                @elseif ($data->status_pengiriman === 'sedang dikirim') text-red-600
                                @elseif ($data->status_pengiriman === 'menuju alamat') text-blue-600
                                @elseif ($data->status_pengiriman === 'pesanan diterima') text-green-600
                                @endif">
                                {{ ucfirst($data->status_pengiriman) }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Print --}}
                                    <button onclick="printData('{{ $data->resi }}')" class="w-16 bg-green-500 hover:bg-green-600 text-white py-1 rounded text-xs shadow-md shadow-gray-700 flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4h12v5M6 14h12v6H6v-6zM6 14H4a2 2 0 01-2-2V9a2 2 0 012-2h16a2 2 0 012 2v3a2 2 0 01-2 2h-2" />
                                        </svg>
                                    </button>

                                    {{-- Tombol "Kurir" hanya muncul jika `kurir_id` belum ditentukan (NULL) --}}
                                    @if (empty($data->kurir_id))
                                        <button class="w-16 bg-red-500 text-white py-1 rounded text-xs hover:bg-red-600 shadow-md shadow-gray-700" onclick="openModal('{{ $data->id }}')">Kurir</button>
                                    @endif

                                    {{-- Tombol "Detail" --}}
                                    <button
                                        class="px-3 bg-blue-500 text-white py-1 rounded text-xs hover:bg-blue-600 shadow-md shadow-gray-700 px-4 py-2"
                                        onclick="showDetailModal(
                                            '{{ $data->resi }}',
                                            '{{ $data->nama_pengirim }}',
                                            '{{ $data->alamat_penjemputan }}',
                                            '{{ $data->nama_penerima }}',
                                            '{{ $data->alamat_tujuan }}',
                                            '{{ $data->nama_kurir ?? 'Belum Ditentukan' }}', {{-- Menggunakan nama_kurir untuk detail --}}
                                            '{{ \Carbon\Carbon::parse($data->tanggal_pemesanan)->format('Y-m-d') }}',
                                            '{{ $data->berat }}',
                                            '{{ number_format($data->harga, 0, ',', '.') }}',
                                            '{{ ucfirst($data->status_pengiriman) }}',
                                            '{{ $data->catatan ?? '' }}' {{-- Meneruskan catatan ke modal detail --}}
                                        )"
                                    > Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-4 text-center text-gray-500">Tidak ada data pengiriman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Laravel Pagination Links --}}
        <div class="mt-4">
            {{ $pengiriman->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail Pengiriman --}}
<div id="modalDetail" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-4 rounded-lg shadow-md shadow-gray-700 w-[1000px] max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center">
            <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-full">
            <h5 class="text-xl font-semibold flex-1 ml-4">Detail Pengiriman</h5>
            <button onclick="closeDetailModal()" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <hr class="my-4 border-gray-300">

        <form id="detailForm" class="text-sm space-y-2"> {{-- space-y-2 untuk jarak vertikal lebih kecil --}}
            <div class="flex items-center gap-4">
                <label for="resiDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Resi</label> {{-- Lebar label diperlebar --}}
                <input type="text" id="resiDetail" name="resi" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly> {{-- py-1.5 untuk tinggi input lebih kecil, text-sm dipertahankan untuk keterbacaan --}}
            </div>
            <div class="flex items-center gap-4">
                <label for="pengirimDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Pengirim</label>
                <input type="text" id="pengirimDetail" name="pengirim" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatJemputDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Penjemputan</label>
                <textarea id="alamatJemputDetail" name="alamatJemput" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="penerimaDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Nama Penerima</label>
                <input type="text" id="penerimaDetail" name="penerima" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="alamatTujuanDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Alamat Tujuan</label>
                <textarea id="alamatTujuanDetail" name="alamatTujuan" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
            <div class="flex items-center gap-4">
                <label for="tanggalDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Tanggal Pemesanan</label>
                <input type="text" id="tanggalDetail" name="tanggal" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="beratDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Berat (kg)</label>
                <input type="text" id="beratDetail" name="berat" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="hargaDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Harga (Rp)</label>
                <input type="text" id="hargaDetail" name="harga" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="kurirDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Kurir</label>
                <input type="text" id="kurirDetail" name="kurir" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="statusDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Status</label>
                <input type="text" id="statusDetail" name="status" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly>
            </div>
            <div class="flex items-center gap-4">
                <label for="catatanDetail" class="w-40 text-left font-medium text-gray-700 after:content-[':']">Catatan</label>
                <textarea id="catatanDetail" name="catatan" rows="2" class="flex-1 pl-4 py-1.5 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-sm" readonly></textarea>
            </div>
        </form>

        {{-- Tombol Tutup --}}
        <div class="flex justify-end mt-6">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal Tentukan Kurir --}}
<div id="modalTentukanKurir" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-md shadow-gray-700 w-[1000px]">
        <div class="flex justify-between items-center">
            {{-- Sesuaikan path gambar logo Anda --}}
            <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo" class="w-16 h-16 object-cover rounded-full">
            <h5 class="text-xl font-semibold flex-1 ml-4">Penjadwalan Kurir</h5>
            <button class="text-gray-500 hover:text-gray-700 text-2xl" onclick="closeModal()">&times;</button>
        </div>
        <hr class="my-4 border-gray-300">

        <form action="{{ route('assign.kurir') }}" method="POST" id="formTentukanKurir">
            @csrf
            {{-- Hidden input untuk ID pengiriman. Ini akan diisi oleh JavaScript. --}}
            <input type="hidden" id="shipmentIdToAssign" name="shipment_id">
            {{-- Hidden input untuk ID kurir yang dipilih --}}
            <input type="hidden" id="selectedKurirId" name="kurir_id">

            {{-- Content for "Pilih Berdasarkan Wilayah" (now the only mode) --}}
            <div id="contentWilayah" class="tab-content"> {{-- ID contentWilayah tetap dipertahankan untuk JavaScript --}}
                <div class="mb-4">
                    <label for="wilayahPengiriman" class="block text-sm font-medium text-gray-700">Wilayah Pengiriman</label>
                    <select id="wilayahPengiriman" name="wilayahPengiriman"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih wilayah pengiriman...</option>
                        <option value="Batam Centre">Batam Centre</option>
                        <option value="Botania">Botania</option>
                        <option value="Batu Aji">Batu Aji</option>
                        <option value="Punggur">Punggur</option>
                        <option value="Piayu">Piayu</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="kurirSelect" class="block text-sm font-medium text-gray-700">Pilih Kurir</label>
                    <select id="kurirSelect" name="kurir_dropdown"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih kurir...</option>
                        {{-- Opsi kurir akan diisi secara dinamis melalui JavaScript --}}
                    </select>
                </div>
            </div>

            {{-- Common Fields --}}
            <div class="mb-4">
                <label for="tanggalPengiriman" class="block text-sm font-medium text-gray-700">Tanggal Pengiriman</label>
                <input type="date" id="tanggalPengiriman" name="tanggalPengiriman" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Masukkan catatan khusus"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()"
                    class="text-sm py-3 px-5 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md shadow-gray-700">Batal</button>
                <button type="submit"
                    class="text-sm py-3 px-5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-md shadow-gray-700">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wilayahSelect = document.getElementById('wilayahPengiriman');
        const kurirSelectDropdown = document.getElementById('kurirSelect');
        const formTentukanKurir = document.getElementById('formTentukanKurir');
        const shipmentIdToAssignInput = document.getElementById('shipmentIdToAssign');
        const selectedKurirIdInput = document.getElementById('selectedKurirId');
        const modalTentukanKurir = document.getElementById('modalTentukanKurir');
        const modalDetail = document.getElementById('modalDetail');
        // Pastikan elemen header Anda memiliki ID 'admin-header'
        const adminHeader = document.getElementById('admin-header');

        // Periksa elemen yang *masih* ada
        if (!wilayahSelect || !kurirSelectDropdown || !formTentukanKurir || !shipmentIdToAssignInput || !selectedKurirIdInput || !modalTentukanKurir || !modalDetail || !adminHeader) {
            console.error('One or more critical HTML elements for modal/form are missing. Please check your IDs and ensure admin-header exists.');
            return;
        }

        window.openModal = function(shipmentId) {
            modalTentukanKurir.classList.remove('hidden');
            // Add the class to darken the header
            adminHeader.classList.add('header-darken');
            shipmentIdToAssignInput.value = shipmentId;
            // Reset modal state
            wilayahSelect.value = '';
            kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
            selectedKurirIdInput.value = ''; // Ensure no courier ID is pre-selected
            document.getElementById('tanggalPengiriman').valueAsDate = new Date();
            document.getElementById('catatan').value = '';
        };

        window.closeModal = function() {
            modalTentukanKurir.classList.add('hidden');
            // Remove the class to revert the header
            adminHeader.classList.remove('header-darken');
        };

        window.showDetailModal = function(resi, pengirim, alamatJemput, penerima, alamatTujuan, kurir, tanggalPemesanan, berat, harga, status, catatan) {
            document.getElementById('resiDetail').value = resi;
            document.getElementById('pengirimDetail').value = pengirim;
            document.getElementById('alamatJemputDetail').value = alamatJemput;
            document.getElementById('penerimaDetail').value = penerima;
            document.getElementById('alamatTujuanDetail').value = alamatTujuan;
            document.getElementById('kurirDetail').value = kurir;
            document.getElementById('tanggalDetail').value = tanggalPemesanan;
            document.getElementById('beratDetail').value = berat;
            document.getElementById('hargaDetail').value = harga;
            document.getElementById('statusDetail').value = status;
            document.getElementById('catatanDetail').value = catatan;
            modalDetail.classList.remove('hidden');
            // Add the class to darken the header
            adminHeader.classList.add('header-darken');
        };

        window.closeDetailModal = function() {
            modalDetail.classList.add('hidden');
            // Remove the class to revert the header
            adminHeader.classList.remove('header-darken');
        };

        window.printData = function(resi) {
            alert('Fungsi print untuk Resi: ' + resi + ' akan ditambahkan di sini.');
            // Implement printing logic here
            // Example: window.open('/print/resi/' + resi, '_blank');
        };

        wilayahSelect.addEventListener('change', async function() {
            const selectedWilayah = this.value;
            kurirSelectDropdown.innerHTML = '<option value="">Loading...</option>';
            selectedKurirIdInput.value = ''; // Reset selected ID when region changes

            if (selectedWilayah) {
                const url = `{{ route('kurir.byWilayah', ['wilayah' => '__WILAYAH__']) }}`.replace('__WILAYAH__', encodeURIComponent(selectedWilayah));
                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                    }
                    const data = await response.json();
                    kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
                    if (data.length === 0) {
                        kurirSelectDropdown.innerHTML = '<option value="">Tidak ada kurir untuk wilayah ini</option>';
                    } else {
                        data.forEach(kurir => {
                            const option = document.createElement('option');
                            option.value = kurir.id;
                            option.textContent = kurir.username;
                            kurirSelectDropdown.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching courier data by region:', error);
                    kurirSelectDropdown.innerHTML = '<option value="">Gagal memuat kurir</option>';
                    alert('Gagal memuat daftar kurir: ' + error.message);
                }
            } else {
                kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
            }
        });

        // Update hidden kurir_id input when dropdown selection changes
        kurirSelectDropdown.addEventListener('change', function() {
            // Hanya set selectedKurirIdInput jika nilai yang dipilih bukan kosong
            if (this.value) {
                selectedKurirIdInput.value = this.value;
            } else {
                selectedKurirIdInput.value = ''; // Jika memilih "Pilih kurir...", reset ID
            }
        });

        formTentukanKurir.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Basic validation for courier selection
            if (!selectedKurirIdInput.value) {
                alert('Harap pilih kurir terlebih dahulu dari daftar wilayah.');
                return;
            }

            const formData = new FormData(this);
            formData.set('kurir_id', selectedKurirIdInput.value); // Pastikan ini yang dikirim

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    let errorMessage = errorData.message || `Server error! Status: ${response.status}`;
                    if (errorData.errors) {
                        errorMessage += '\n\nDetail Kesalahan:\n' + Object.values(errorData.errors).flat().join('\n');
                    }
                    alert('Terjadi kesalahan saat menetapkan kurir: ' + errorMessage);
                } else {
                    const successData = await response.json();
                    alert(successData.message);
                    closeModal(); // Tutup modal setelah berhasil
                    location.reload(); // Muat ulang halaman untuk melihat perubahan
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('Gagal mengirim permintaan: ' + error.message);
            }
        });

        function ucfirst(str) {
            if (!str) return str;
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
</script>

<style>
    /* CSS untuk menggelapkan header saat modal terbuka */
    .header-darken {
        filter: brightness(0.5); /* Menggelapkan sebesar 50% */
        transition: filter 0.3s ease-in-out;
        pointer-events: none; /* Menonaktifkan interaksi klik */
    }
</style>
@endsection