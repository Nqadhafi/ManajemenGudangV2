<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Imports\BarangImport;


class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with(['kategori', 'supplier'])->get();
        return view('barangs.index', compact('barangs'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barangs.create', compact('kategoris', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'stok_minimum' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id',
            'id_supplier' => 'required|exists:supplier,id'
        ]);

        Barang::create($request->all());

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barangs.edit', compact('barang', 'kategoris', 'suppliers'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'stok_minimum' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id',
            'id_supplier' => 'required|exists:supplier,id'
        ]);

        $barang->update($request->all());

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        if($barang->transaksis()->count() > 0) {
            return redirect()->route('barangs.index')->with('error', 'Barang tidak bisa dihapus karena sudah memiliki transaksi');
        }
        
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus');
    }

    public function kartuStok($id)
    {
        $barang = Barang::findOrFail($id);
        $transaksis = Transaksi::where('id_barang', $id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        
        return view('barangs.kartu-stok', compact('barang', 'transaksis'));
    }
    public function export()
{
    return Excel::download(new BarangExport, 'barangs.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'file'           => 'required|file|mimes:xlsx,xls,csv|max:20480',
        'auto_create'    => 'nullable|boolean',
        'case_sensitive' => 'nullable|boolean',
    ]);

    $import = new BarangImport(
        (bool)$request->boolean('auto_create'),
        (bool)$request->boolean('case_sensitive')
    );

    Excel::import($import, $request->file('file'));

    $failures = $import->failures();
    if ($failures->isNotEmpty()) {
        return back()->with('error','Sebagian baris gagal diimport.')->with('import_failures',$failures);
    }

    return back()->with('success','Import Barang selesai.');
}


}