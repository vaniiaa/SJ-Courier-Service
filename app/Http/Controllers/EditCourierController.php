<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurir;
use Illuminate\Support\Facades\Hash;

class EditCourierController extends Controller
{
    public function editKurir($id)
{
    $kurir = Kurir::findOrFail($id);
    return view('admin.edit_kurir', compact('kurir'));
}

public function updateKurir(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string',
        'email' => 'required|email',
        'no_hp' => 'required',
        'alamat' => 'required',
        'wilayah_pengiriman' => 'required',
        'username' => 'required',
    ]);

    $kurir = Kurir::findOrFail($id);
    $kurir->update($request->all());

    return redirect()->route('admin.kelola_kurir')->with('success', 'Data kurir berhasil diperbarui.');
}
}
