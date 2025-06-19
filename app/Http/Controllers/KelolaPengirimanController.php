<?php

/**
 * Nama File: KelolaPengirimanController.php
 * Deskripsi: Controller ini menangani pengelolaan data pengiriman,
 * termasuk menampilkan daftar pengiriman untuk admin dan kurir,
 * pencarian kurir berdasarkan wilayah atau username, dan menetapkan kurir untuk pengiriman.
 * Dibuat Oleh: [Aulia Sabrina] - [3312301002]
 * Tanggal: 25 Mei 2025
 */

 // Mendefinisikan namespace untuk controller.
namespace App\Http\Controllers; 

// Mengimpor model Pengiriman untuk berinteraksi dengan data pengiriman.
use App\Models\Pengiriman; 

// Mengimpor model Kurir untuk berinteraksi dengan data kurir.
use App\Models\Kurir; 

// Mengimpor kelas Request untuk menangani permintaan HTTP.
use Illuminate\Http\Request; 

// Mengimpor Carbon untuk kemudahan manipulasi tanggal dan waktu.
use Carbon\Carbon; 

// Mengimpor Validator facade untuk validasi data manual.
use Illuminate\Support\Facades\Validator; 

// Mengimpor Auth facade untuk mengelola autentikasi pengguna.
use Illuminate\Support\Facades\Auth; 

// Mendefinisikan kelas controller.
class KelolaPengirimanController extends Controller 
{
    /**
     * Menampilkan daftar pengiriman untuk halaman admin.
     * Data diurutkan dari yang terlama ke terbaru (terbaru di akhir daftar) dan mendukung pencarian.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi parameter pencarian.
     * @return \Illuminate\View\View Mengembalikan view 'admin.kelola_pengiriman' dengan data pengiriman.
     */
    public function index(Request $request)
    {
        // Memulai query untuk mengambil semua data pengiriman, diurutkan berdasarkan waktu pembuatan secara ascending.
        $query = Pengiriman::orderBy('created_at', 'asc');

        // Menambahkan filter pencarian jika ada input 'search'.
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                // Mencari berdasarkan nomor resi, nama pengirim, atau nama penerima.
                $q->where('resi', 'like', $searchTerm)
                  ->orWhere('nama_pengirim', 'like', $searchTerm)
                  ->orWhere('nama_penerima', 'like', $searchTerm);
            });
        }

        // Mengambil data pengiriman dengan paginasi, 10 item per halaman.
        $pengiriman = $query->paginate(10);

        // Mengembalikan view admin dengan data pengiriman.
        return view('admin.kelola_pengiriman', compact('pengiriman'));
    }

    /**
     * Menampilkan daftar pengiriman yang ditugaskan kepada kurir yang sedang login.
     * Data diurutkan dari yang terbaru ke terlama (terbaru di awal daftar) dan mendukung pencarian.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi parameter pencarian.
     * @return \Illuminate\View\View Mengembalikan view 'kurir.daftar_pengiriman' dengan data pengiriman kurir.
     */
    public function daftarPengirimanKurir(Request $request)
    {
        // Memastikan pengguna adalah kurir yang terautentikasi. Jika tidak, arahkan kembali ke login kurir.
        if (!Auth::guard('kurir')->check()) {
            return redirect()->route('kurir.login')->with('error', 'Silakan masuk sebagai kurir untuk melihat daftar pengiriman.');
        }

        // Mendapatkan ID kurir yang sedang login.
        $kurirId = Auth::guard('kurir')->id();

        // Membangun query untuk pengiriman yang ditugaskan kepada kurir ini.
        $query = Pengiriman::where('kurir_id', $kurirId);

        // Menambahkan fungsionalitas pencarian jika ada input 'search'.
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Mencari berdasarkan nomor resi, nama pengirim, atau nama penerima.
                $q->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
            });
        }

        // Mengambil pengiriman dengan paginasi, diurutkan dari yang terbaru ke terlama.
        $pengiriman = $query->orderBy('tanggal_pemesanan', 'desc')
                            ->paginate(10);

        // Mengembalikan view daftar pengiriman kurir.
        return view('kurir.daftar_pengiriman', compact('pengiriman'));
    }

     public function updateStatus(Request $request)
    {
        // Memastikan pengguna adalah kurir yang terautentikasi. Jika tidak, arahkan kembali ke login kurir.
        if (!Auth::guard('kurir')->check()) {
            return redirect()->route('kurir.login')->with('error', 'Silakan masuk sebagai kurir untuk mengupdate status.');
        }

        // Mendapatkan ID kurir yang sedang login.
        $kurirId = Auth::guard('kurir')->id();

        // Membangun query untuk pengiriman yang ditugaskan kepada kurir ini.
        $query = Pengiriman::where('kurir_id', $kurirId);

        // Menambahkan fungsionalitas pencarian jika ada input 'search'.
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Mencari berdasarkan nomor resi, nama pengirim, atau nama penerima.
                $q->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
            });
        }

        // Mengambil pengiriman dengan paginasi, diurutkan dari yang terbaru ke terlama.
        $pengiriman = $query->orderBy('tanggal_pemesanan', 'desc')
                            ->paginate(10);

        // Mengembalikan view daftar pengiriman kurir.
        return view('kurir.kelola_status', compact('pengiriman'));
    }






   public function statusPengiriman(Request $request)
    {
        $search = $request->input('search');

        $query = Pengiriman::query();

        if ($search) {
            $query->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
        }

        // Ambil data pengiriman dengan paginasi
        // PASTIKAN BARIS INI ADA DAN MENGHASILKAN VARIABEL $pengiriman
        $pengiriman = $query->latest()->paginate(10);

        // Kirim variabel $pengiriman ke view
        return view('admin.status_pengiriman', compact('pengiriman'));
    }

public function historyPengiriman(Request $request)
    {
        $search = $request->input('search');

        $query = Pengiriman::query();

        if ($search) {
            $query->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
        }

        // Ambil data pengiriman dengan paginasi
        // PASTIKAN BARIS INI ADA DAN MENGHASILKAN VARIABEL $pengiriman
        $pengiriman = $query->latest()->paginate(10);

        // Kirim variabel $pengiriman ke view
        return view('admin.history_pengiriman', compact('pengiriman'));
    }

    /**
     * Mengambil daftar kurir berdasarkan wilayah pengiriman yang ditentukan.
     * Hasilnya dikembalikan dalam format JSON, berguna untuk dropdown dinamis.
     *
     * @param  string  $wilayah Wilayah pengiriman yang dicari.
     * @return \Illuminate\Http\JsonResponse Mengembalikan daftar kurir (id dan username) dalam JSON.
     */
    public function getKurirByWilayah($wilayah)
    {
        // Mengubah wilayah input menjadi huruf kecil dan menghapus spasi di awal/akhir.
        $wilayahLower = strtolower(trim($wilayah));

        // Mencari kurir yang wilayah pengirimannya cocok (case-insensitive).
        $kurirs = Kurir::whereRaw('LOWER(wilayah_pengiriman) LIKE ?', ['%' . $wilayahLower . '%'])
                       ->select('id', 'username') // Hanya mengambil ID dan username.
                       ->get();

        // Mengembalikan array kosong jika tidak ada kurir ditemukan.
        if ($kurirs->isEmpty()) {
            return response()->json([]);
        }

        // Mengembalikan data kurir dalam format JSON.
        return response()->json($kurirs);
    }

    /**
     * Mencari kurir berdasarkan username untuk fitur autokomplit.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi query username.
     * @return \Illuminate\Http\JsonResponse Mengembalikan daftar kurir (id dan username) dalam JSON.
     */
    public function getCourierByUsername(Request $request)
    {
        // Mengambil query username dari request.
        $usernameQuery = $request->input('username');

        // Mengembalikan array kosong jika query username kosong.
        if (empty($usernameQuery)) {
            return response()->json([]);
        }

        // Mencari kurir yang username-nya cocok (case-insensitive), dibatasi hingga 10 hasil.
        $couriers = Kurir::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($usernameQuery) . '%'])
                         ->select('id', 'username')
                         ->limit(10)
                         ->get();

        // Mengembalikan data kurir dalam format JSON.
        return response()->json($couriers);
    }

    /**
     * Menetapkan (assign) kurir ke sebuah pengiriman dan memperbarui statusnya.
     * Ini juga menyimpan tanggal pengiriman dan catatan.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi ID pengiriman, ID kurir, tanggal, dan catatan.
     * @return \Illuminate\Http\JsonResponse Mengembalikan pesan sukses atau error dalam JSON.
     */
    public function assignKurir(Request $request)
    {
        // Melakukan validasi data yang diterima dari request.
        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required|exists:shipments,id', // ID pengiriman wajib dan harus ada di tabel 'pengiriman'.
            'kurir_id' => 'required|exists:kurir,id', // ID kurir wajib dan harus ada di tabel 'kurir'.
            'tanggalPengiriman' => 'required|date', // Tanggal pengiriman wajib dan harus format tanggal.
            'catatan' => 'nullable|string|max:500', // Catatan opsional, string, maks 500 karakter.
        ]);

        // Jika validasi gagal, kembalikan respon JSON dengan error validasi.
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()->toArray()
            ], 422); // Status HTTP 422 (Unprocessable Entity)
        }

        try {
            // Mencari pengiriman dan kurir berdasarkan ID. Jika tidak ditemukan, akan melempar exception.
            $pengiriman = Pengiriman::findOrFail($request->shipment_id);
            $kurir = Kurir::findOrFail($request->kurir_id);

            // Memperbarui data pengiriman dengan ID dan nama kurir, tanggal, catatan, dan status baru.
            $pengiriman->kurir_id = $kurir->id;
            $pengiriman->nama_kurir = $kurir->username; // Menyimpan username kurir untuk kemudahan.
            $pengiriman->tanggal_pengiriman = Carbon::parse($request->tanggalPengiriman); // Mengurai tanggal.
            $pengiriman->catatan = $request->catatan;
            $pengiriman->status_pengiriman = 'Menunggu Konfirmasi Kurir'; // Mengubah status pengiriman.
            $pengiriman->save(); // Menyimpan perubahan ke database.

            // Mengembalikan respon sukses dalam format JSON.
            return response()->json([
                'success' => true,
                'message' => 'Kurir berhasil ditetapkan dan status diubah!',
            ]);
        } catch (\Exception $e) {
            // Menangkap error jika terjadi dan mengembalikan respon error dalam format JSON.
            return response()->json([
                'success' => false,
                'message' => 'Gagal menetapkan kurir: ' . $e->getMessage(),
            ], 500); // Status HTTP 500 (Internal Server Error)
        }
    }
}