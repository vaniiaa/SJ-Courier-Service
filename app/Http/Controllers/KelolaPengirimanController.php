<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class KelolaPengirimanController extends Controller
{
    /**
     * Display a listing of the shipments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager load the 'kurir' relationship to avoid N+1 query problem
        // and get courier details if needed, though we primarily use nama_kurir column.
        $query = Pengiriman::orderBy('created_at', 'desc');

        // Apply search filter if present
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('resi', 'like', $searchTerm)
                  ->orWhere('nama_pengirim', 'like', $searchTerm)
                  ->orWhere('nama_penerima', 'like', $searchTerm);
            });
        }

        $pengiriman = $query->paginate(10); // Paginate the results

        return view('admin.kelola_pengiriman', compact('pengiriman'));
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

        // Get couriers based on 'wilayah_pengiriman' (case-insensitive and partial match)
        $kurirs = Kurir::whereRaw('LOWER(wilayah_pengiriman) LIKE ?', ['%' . $wilayahLower . '%'])
                       ->select('id', 'username') // Only select id and username
                       ->get();

        if ($kurirs->isEmpty()) {
            return response()->json([]); // Return empty array if no couriers found
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

        // Search for couriers by username (case-insensitive and partial match)
        $couriers = Kurir::whereRaw('LOWER(username) LIKE ?', ['%' . strtolower($usernameQuery) . '%'])
                           ->select('id', 'username')
                           ->limit(10) // Limit results for better autocomplete performance
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
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required|exists:pengiriman,id',
            'kurir_id' => 'required|exists:kurir,id', // Validate the courier's ID
            'tanggalPengiriman' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        try {
            // Find the shipment by its ID
            $pengiriman = Pengiriman::findOrFail($request->shipment_id);

            // Find the courier by the validated ID
            $kurir = Kurir::findOrFail($request->kurir_id);

            // Update shipment details
            $pengiriman->kurir_id = $kurir->id;
            $pengiriman->nama_kurir = $kurir->username; // Store courier's username in nama_kurir column
            $pengiriman->tanggal_pengiriman = Carbon::parse($request->tanggalPengiriman);
            $pengiriman->catatan = $request->catatan;
            $pengiriman->status_pengiriman = 'sedang dikirim'; // Update shipment status
            $pengiriman->save(); // Save changes to the database

            // Return a success JSON response
            return response()->json([
                'success' => true,
                'message' => 'Kurir berhasil ditetapkan dan status diubah!',
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and return an error JSON response
            return response()->json([
                'success' => false,
                'message' => 'Gagal menetapkan kurir: ' . $e->getMessage(),
            ], 500);
        }
    }
}