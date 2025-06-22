<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Menampilkan halaman untuk mengelola semua pengiriman.
     */
    public function index(Request $request)
    {
        // Memulai query dengan eager loading untuk mengambil data dari tabel lain secara efisien
        $query = Shipment::with(['order.sender', 'courier', 'order.payments'])
                         ->latest(); // Mengurutkan dari yang paling baru

        // Logika untuk fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%') // Cari berdasarkan nomor resi
                  ->orWhereHas('order.sender', function($q_user) use ($search) {
                      $q_user->where('name', 'like', '%' . $search . '%'); // Cari berdasarkan nama pengirim
                  })
                  ->orWhereHas('order', function($q_order) use ($search) {
                      $q_order->where('receiverName', 'like', '%' . $search . '%'); // Cari berdasarkan nama penerima
                  })
                  ->orWhereHas('courier', function($q_courier) use ($search) {
                        $q_courier->where('name', 'like', '%' . $search . '%'); // Cari berdasarkan nama kurir
                  });
            });
        }

        // Mengambil data dengan paginasi (10 data per halaman)
        // withQueryString() agar filter pencarian tidak hilang saat pindah halaman
        $shipments = $query->paginate(10)->withQueryString();

        return view('admin.kelola_pengiriman', compact('shipments'));
    }
}