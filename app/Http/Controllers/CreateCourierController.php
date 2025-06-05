<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurir;
use Illuminate\Support\Facades\Hash;

class CreateCourierController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->input('search');

        $kurirs = Kurir::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                         ->orWhere('username', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10); 

        return view('admin.kelola_kurir', compact('kurirs'));
    }

    public function create()
    {
        return view('admin.tambah_kurir');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:kurir',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'wilayah_pengiriman' => 'required|string|max:255',
            'username' => 'required|string|unique:kurir,username',
            'password' => 'required|string|min:6',
        ]);

        Kurir::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'wilayah_pengiriman' => $request->wilayah_pengiriman,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil ditambahkan.');
    }
}
