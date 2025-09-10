<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierExport;
use App\Imports\SupplierImport;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'no_hp_supplier' => 'nullable',
            'alamat' => 'nullable'
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'no_hp_supplier' => 'nullable',
            'alamat' => 'nullable'
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        if($supplier->barangs()->count() > 0) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier tidak bisa dihapus karena masih memiliki barang');
        }
        
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }
    public function export() { return Excel::download(new SupplierExport, 'suppliers.xlsx'); }

public function import(Request $request)
{
    $request->validate([
        'file'           => 'required|file|mimes:xlsx,xls,csv|max:20480',
        'case_sensitive' => 'nullable|boolean',
    ]);

    $import = new SupplierImport((bool)$request->boolean('case_sensitive'));
    Excel::import($import, $request->file('file'));

    $failures = $import->failures();
    if ($failures->isNotEmpty()) {
        return back()->with('error','Sebagian baris gagal diimport.')->with('import_failures',$failures);
    }

    return back()->with('success','Import Supplier selesai.');
}
}