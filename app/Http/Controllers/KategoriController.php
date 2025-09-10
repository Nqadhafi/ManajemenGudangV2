<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriExport;
use App\Imports\KategoriImport;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategories.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategori'
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategories.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|unique:kategori,nama,'.$kategori->id
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        if($kategori->barangs()->count() > 0) {
            return redirect()->route('kategoris.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki barang');
        }
        
        $kategori->delete();
        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dihapus');
    }
    public function export() { return Excel::download(new KategoriExport, 'kategoris.xlsx'); }

public function import(Request $request)
{
    $request->validate([
        'file'           => 'required|file|mimes:xlsx,xls,csv|max:20480',
        'case_sensitive' => 'nullable|boolean',
    ]);

    $import = new KategoriImport((bool)$request->boolean('case_sensitive'));
    Excel::import($import, $request->file('file'));

    $failures = $import->failures();
    if ($failures->isNotEmpty()) {
        return back()->with('error','Sebagian baris gagal diimport.')->with('import_failures',$failures);
    }

    return back()->with('success','Import Kategori selesai.');
}
}