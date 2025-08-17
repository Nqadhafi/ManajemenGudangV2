<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all();
        return view('divisis.index', compact('divisis'));
    }

    public function create()
    {
        return view('divisis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:divisi'
        ]);

        Divisi::create($request->all());

        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil ditambahkan');
    }

    public function edit(Divisi $divisi)
    {
        return view('divisis.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|unique:divisi,nama,'.$divisi->id
        ]);

        $divisi->update($request->all());

        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil diupdate');
    }

    public function destroy(Divisi $divisi)
    {
        if($divisi->karyawans()->count() > 0) {
            return redirect()->route('divisis.index')->with('error', 'Divisi tidak bisa dihapus karena masih memiliki karyawan');
        }
        
        $divisi->delete();
        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil dihapus');
    }
}