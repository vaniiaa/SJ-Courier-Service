<?php

namespace App\Http\Controllers;

use App\Models\Kurir;

class DeleteCourierController extends Controller
{
    public function __invoke($id)
    {
        $kurir = Kurir::findOrFail($id);
        $kurir->delete();

        return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil dihapus.');
    }
}
