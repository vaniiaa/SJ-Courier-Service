<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Penting: Import Auth Facade

class KelolaPengirimanController extends Controller
{
    /**
     * Display a listing of the shipments for Admin.
     * Data terbaru akan muncul di akhir daftar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query dasar untuk mengambil data pengiriman
        // Urutkan berdasarkan created_at secara ascending (dari terlama ke terbaru)
        // Ini akan menempatkan data terbaru di bagian bawah daftar
        $query = Pengiriman::orderBy('created_at', 'asc');

        // Tambahkan fungsionalitas pencarian jika ada input search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('resi', 'like', $searchTerm)
                  ->orWhere('nama_pengirim', 'like', $searchTerm)
                  ->orWhere('nama_penerima', 'like', $searchTerm);
            });
        }

        // Ambil data pengiriman dengan pagination
        $pengiriman = $query->paginate(10); // Sesuaikan jumlah item per halaman sesuai kebutuhan

        return view('admin.kelola_pengiriman', compact('pengiriman'));
    }

    /**
     * Display a listing of shipments assigned to the logged-in courier.
     * Data terbaru akan muncul di awal daftar untuk kurir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
     public function daftarPengirimanKurir(Request $request)
    {
        // Pastikan kurir terautentikasi dengan 'kurir' guard
        if (!Auth::guard('kurir')->check()) {
            return redirect()->route('kurir.login')->with('error', 'Silakan masuk sebagai kurir untuk melihat daftar pengiriman.');
        }

        $kurirId = Auth::guard('kurir')->id();

        // Mulai membangun query
        $query = Pengiriman::where('kurir_id', $kurirId);

        // Tangani fungsionalitas pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
            });
        }

        // Ambil pengiriman yang ditugaskan kepada kurir yang sedang login
        // dan lakukan pagination. Urutkan berdasarkan tanggal_pemesanan secara descending (terbaru di atas)
        $pengiriman = $query->orderBy('tanggal_pemesanan', 'desc') // Urutkan dari yang terbaru ke terlama
                             ->paginate(10); // 10 item per halaman

        return view('kurir.daftar_pengiriman', compact('pengiriman'));
    }


    /**
     * Get couriers by region for dropdown selection.
     *
     * @param  string  $wilayah
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKurirByWilayah($wilayah)
    {
        $wilayahLower = strtolower(trim($wilayah));

        $kurirs = Kurir::whereRaw('LOWER(wilayah_pengiriman) LIKE ?', ['%' . $wilayahLower . '%'])
                       ->select('id', 'username')
                       ->get();

        if ($kurirs->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($kurirs);
    }

    /**
     * Search couriers by username for autocomplete feature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourierByUsername(Request $request)
    {
        $usernameQuery = $request->input('username');

        if (empty($usernameQuery)) {
            return response()->json([]);
        }

        $couriers = Kurir::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($usernameQuery) . '%'])
                         ->select('id', 'username')
                         ->limit(10)
                         ->get();

        return response()->json($couriers);
    }

    /**
     * Assign a courier to a shipment and update its status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignKurir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required|exists:pengiriman,id',
            'kurir_id' => 'required|exists:kurir,id',
            'tanggalPengiriman' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        try {
            $pengiriman = Pengiriman::findOrFail($request->shipment_id);
            $kurir = Kurir::findOrFail($request->kurir_id);

            $pengiriman->kurir_id = $kurir->id;
            $pengiriman->nama_kurir = $kurir->username;
            $pengiriman->tanggal_pengiriman = Carbon::parse($request->tanggalPengiriman);
            $pengiriman->catatan = $request->catatan;
            $pengiriman->status_pengiriman = 'sedang dikirim';
            $pengiriman->save();

            return response()->json([
                'success' => true,
                'message' => 'Kurir berhasil ditetapkan dan status diubah!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menetapkan kurir: ' . $e->getMessage(),
            ], 500);
        }
    }
}