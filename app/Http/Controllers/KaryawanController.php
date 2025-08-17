<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with(['user', 'divisi'])->get();
        return view('karyawans.index', compact('karyawans'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('karyawan')->get();
        $divisis = Divisi::all();
        return view('karyawans.create', compact('users', 'divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_divisi' => 'required|exists:divisi,id',
            'nama' => 'required',
            'nik' => 'required|unique:karyawan,nik',
            'alamat' => 'nullable',
            'nomor_hp' => 'nullable'
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawans.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        $users = User::all();
        $divisis = Divisi::all();
        return view('karyawans.edit', compact('karyawan', 'users', 'divisis'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_divisi' => 'required|exists:divisi,id',
            'nama' => 'required',
            'nik' => 'required|unique:karyawan,nik,'.$karyawan->id,
            'alamat' => 'nullable',
            'nomor_hp' => 'nullable'
        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawans.index')->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawans.index')->with('success', 'Karyawan berhasil dihapus');
    }
}