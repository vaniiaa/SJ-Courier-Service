<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;


class DaftarPengirimanController extends Controller
{
    /**
     * Pastikan semua method di controller ini hanya bisa diakses oleh kurir.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar pengiriman aktif (belum selesai) untuk kurir yang login.
     */
    public function index(Request $request)
    {
        $kurirId = auth()->id();
        $search = $request->input('search');

        // 1. Mulai query builder dengan eager loading untuk performa
        $query = Shipment::with(['order.sender', 'courier'])
                         ->where('courierUserID', $kurirId);

        // 2. Terapkan filter pencarian jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                      ->orWhereHas('order.sender', function($subq) use ($search) {
                          $subq->where('name', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('order', function($subq) use ($search) {
                          $subq->where('receiverName', 'like', '%' . $search . '%');
                      });
            });
        }

        // 3. Urutkan dan lakukan paginasi SETELAH semua kondisi diterapkan
        $shipments = $query->latest()->paginate(10);

        return view('kurir.daftar_pengiriman', compact('shipments'));
    }
}
