<?php
/**
 * Nama File: KelolaStatusController.php
 * Deskripsi: Controller ini menangani pengelolaan status pengiriman
 * menampilkan history pengiriman, mengunduh resi PDF,
 * preview print resi
 * Dibuat Oleh: [Vania] - [3312301024]
 * Tanggal: 1 Juni 2025
 */
namespace App\Http\Controllers;

//import library dan model
use Illuminate\Http\Request;
use App\Models\Pengiriman; // model untuk tabel pengiriman
use Barryvdh\DomPDF\Facade\Pdf; //untuk membuat file PDF menggunakan dompdf
use Illuminate\Support\Facades\Auth; //autentikasi kurir


class KelolaStatusController extends Controller
{
    /**
     * Menampilkan daftar pengiriman diluar status pesanan selesai
     * pencarian berdasarkan nomor resi dan nama pengirim
     */
    public function index(Request $request)
{
    $search = $request->input('search'); // Ambil input pencarian dari request
    // Ambil data pengiriman yang belum selesai
    $shipments = Pengiriman::whereRaw("TRIM(LOWER(status_pengiriman)) NOT IN ('pesanan selesai')")
        ->when($search, function ($query, $search) {
            // Jika ada pencarian, filter berdasarkan resi, nama pengirim, dan nama penerima 
            return $query->where('resi', 'ilike', "%{$search}%")
                         ->orWhere('nama_pengirim', 'ilike', "%{$search}%")
                         ->orWhere('nama_penerima', 'like', "%{$search}%");
        })
        ->orderBy('id', 'asc') // Urut berdasarkan ID
        ->paginate(10); // Tampilkan 10 data per halama
    // Tampilkan view kelola_status dan kirimkan data pengiriman
    return view('kurir.kelola_status', ['pengiriman' => $shipments]);
}

/**
 * Mengonfirmasi dan memperbarui status pengiriman.
 * Termasuk mengunggah bukti pengiriman jika ada.
 */
public function konfirmasiStatus(Request $request)
{
    // Validasi input form
    $request->validate([
        'shipment_id' => 'required|exists:shipments,id',
        'status_pengiriman' => 'required|string',
        'bukti_pengiriman' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);
    // Ambil data pengiriman berdasarkan ID
    $shipment = Pengiriman::findOrFail($request->shipment_id);

    // Konsistensi: simpan dan cek dalam lowercase
    $status = strtolower(trim($request->status_pengiriman));
    $shipment->status_pengiriman = $status;
    // Jika ada file bukti pengiriman diunggah
    if ($request->hasFile('bukti_pengiriman')) {
        $file = $request->file('bukti_pengiriman');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/bukti_pengiriman', $filename);
        $shipment->bukti_pengiriman = $filename;
    }
    // Simpan perubahan ke database
    $shipment->save();

    // Jika status pengiriman adalah "pesanan selesai", arahkan ke halaman history pengiriman
    if ($status === 'pesanan selesai') {
        return redirect()->route('kurir.history_pengiriman_kurir')->with([
            'success' => 'Pengiriman selesai dan telah dipindahkan ke history pengiriman.',
            'highlight_id' => $shipment->id,
        ]);
    }
    // Jika belum selesai, tetap di halaman kelola status
    return redirect()->route('kurir.kelola_status')->with([
        'success' => 'Status berhasil diperbarui.',
        'highlight_id' => $shipment->id,
    ]);
}

/**
 * Menampilkan riwayat pengiriman yang sudah selesai
 * dan hanya kurir yang sedang login.
 */
 public function history(Request $request)
{
    if (!Auth::guard('kurir')->check()) {
        return redirect()->route('kurir.login')->with('error', 'Silakan masuk sebagai kurir.');
    }

    $kurirId = Auth::guard('kurir')->id();

    $search = $request->input('search');
    // Ambil data pengiriman yang sudah selesai dan sesuai ID kurir
    $shipments = Pengiriman::whereRaw("TRIM(LOWER(status_pengiriman)) = 'pesanan selesai'")
        ->where('kurir_id', $kurirId)
        ->when($search, function ($query, $search) {
            // Filter pencarian berdasarkan resi, nama pengirim, atau nama penerima
            return $query->where(function ($q) use ($search) {
                $q->where('resi', 'like', "%{$search}%")
                  ->orWhere('nama_pengirim', 'like', "%{$search}%")
                  ->orWhere('nama_penerima', 'like', "%{$search}%");
            });
        })
        ->orderBy('tanggal_pengiriman', 'desc') // Urutkan berdasarkan tanggal pengiriman terbaru
        ->paginate(10); // Paginasi 10 data per halaman

    return view('kurir.history_pengiriman_kurir', compact('shipments'));
}

 /**
  * Mengunduh file PDF dari resi pengiriman berdasarkan ID.
  */
public function downloadResi($id)
{
    $shipment = Pengiriman::findOrFail($id); // Ambil data pengiriman

    $pdf = \PDF::loadView('kurir.resi_pdf', compact('shipment'))
               ->setPaper([0, 0, 283.46, 425.2]);
    // Unduh file PDF dengan nama resi
    return $pdf->download('resi_' . $shipment->resi . '.pdf');
}

/**
 * Menampilkan preview resi pengiriman dalam bentuk halaman (tanpa download).
 */
public function printResi($id)
{
    $shipment = Pengiriman::findOrFail($id);

    return view('kurir.resi_pdf', compact('shipment'));
}

}