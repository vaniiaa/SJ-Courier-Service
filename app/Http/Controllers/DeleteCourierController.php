<?php

namespace App\Http\Controllers;

use App\Models\Kurir;

class DeleteCourierController extends Controller
{
    public function destroy($id)
    {
        $kurir = Kurir::findOrFail($id);

        // Cek apakah kurir masih memiliki data pengiriman
        if ($kurir->pengiriman()->exists()) {
            return redirect()->route('admin.kelola_kurir')
                ->with('notif', 'Kurir tidak bisa dihapus karena masih memiliki pengiriman.');
        }

        $kurir->delete();

        return redirect()->route('admin.kelola_kurir')
            ->with('notif', 'Kurir berhasil dihapus.');
    }
}
