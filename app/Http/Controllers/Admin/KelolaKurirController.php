<?php

/**
 * Nama File: KelolaKurirController.php
 * Deskripsi: Controller ini menangani operasi pengelolaan daftar kurir,
 * termasuk menampilkan daftar dengan fitur pencarian dan paginasi.
 * Dibuat Oleh: [Aulia Sabrina] - [3312301002]
 * Tanggal: 25 Mei 2025
 */

 // Mendefinisikan namespace untuk controller.
namespace App\Http\Controllers\Admin; 

// Mengimpor kelas Request untuk menangani permintaan HTTP.
use Illuminate\Http\Request; 

// Mengimpor model Kurir untuk berinteraksi dengan tabel kurir di database.
use App\Models\User;
use App\Models\DeliveryArea; // Mengimpor model Delivery jika diperlukan, meskipun tidak digunakan di sini.
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

// Mendefinisikan kelas controller.
class KelolaKurirController extends Controller 
{
    /**
     * Menampilkan daftar kurir dengan fitur pencarian dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi parameter pencarian.
     * @return \Illuminate\View\View Mengembalikan view 'admin.kelola_kurir' dengan data kurir.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kurirs = User::whereHas('role', function($q) {
                $q->where('role_name', 'courier');
            })
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->appends($request->query());

        return view('admin.kelola_kurir', compact('kurirs'));
    }

    public function create()
{
    $areas = DeliveryArea::all();
    return view('admin.tambah_kurir', compact('areas'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:15' ,
        'address' => 'nullable|string|max:255',
        'area_id' => 'nullable|exists:delivery_area,area_id',
        'email' => 'required|email|unique:users',        
        'password' => 'required|string|min:6',
        // tambahkan validasi lain sesuai kebutuhan
    ]);

    User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'area_id' => $request->area_id,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => 2, // ganti sesuai id role courier di tabel roles
        // field lain sesuai kebutuhan
    ]);

    return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil ditambahkan.');
}

public function edit($id)
{
    $kurir = User::findOrFail($id);
    $areas = DeliveryArea::all();
    return view('admin.edit_kurir', compact('kurir', 'areas'));
}

public function update(Request $request, $id)
{
    $kurir = User::findOrFail($id);
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'area_id' => 'nullable|exists:delivery_area,area_id',
        'email' => 'required|email|unique:users,email,'.$kurir->user_id.',user_id',
        'password' => 'nullable|string|min:6',
        // validasi lain sesuai kebutuhan
    ]);

    $kurir->name = $request->name;
    $kurir->phone = $request->phone;
    $kurir->address = $request->address;
    $kurir->area_id = $request->area_id;
    $kurir->email = $request->email;
    if ($request->filled('password')) {
        $kurir->password = Hash::make($request->password);
    }
    $kurir->save();

    return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil diupdate.');
}

public function destroy($id)
{
    $kurir = User::findOrFail($id);
    $kurir->delete();
    return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil dihapus.');
}

}