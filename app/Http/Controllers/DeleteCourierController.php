<?php

namespace App\Http\Controllers;

use App\Models\Kurir; // Pastikan ini adalah model yang benar untuk kurir

class DeleteCourierController extends Controller
{
    public function destroy($id)
    {
        try {
            $kurir = Kurir::findOrFail($id); // Mencari kurir, akan melempar 404 jika tidak ditemukan

            // Cek apakah kurir masih memiliki data pengiriman
            // Asumsi ada relasi 'pengiriman' di model Kurir Anda
            if ($kurir->pengiriman()->exists()) {
                return redirect()->route('admin.kelola_kurir')
                    ->with('error', 'Kurir **' . $kurir->nama . '** tidak dapat dihapus karena masih memiliki riwayat pengiriman. Harap selesaikan atau hapus semua pengiriman terkait terlebih dahulu.');
            }

            // Simpan nama kurir sebelum dihapus untuk notifikasi
            $kurirNama = $kurir->nama;
            $kurir->delete();

            // Notifikasi sukses
            return redirect()->route('admin.kelola_kurir')
                ->with('success', 'Kurir **' . $kurirNama . '** berhasil dihapus.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika kurir tidak ditemukan
            return redirect()->route('admin.kelola_kurir')
                ->with('error', 'Kurir tidak ditemukan.');
        } catch (\Exception $e) {
            // Tangani error umum lainnya selama proses penghapusan
            return redirect()->route('admin.kelola_kurir')
                ->with('error', 'Terjadi kesalahan saat menghapus kurir. Mohon coba lagi.');
        }
    }
}