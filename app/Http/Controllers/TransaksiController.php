<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Admin - Transaksi Masuk
    public function masukIndex()
    {
        $barangs = Barang::all();
        $karyawans = Karyawan::all();
        return view('transaksi.masuk', compact('barangs', 'karyawans'));
    }

    public function masukStore(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'id_karyawan' => 'required|exists:karyawan,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable'
        ]);

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'jenis_transaksi' => 'masuk',
            'id_barang' => $request->id_barang,
            'id_karyawan' => $request->id_karyawan,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        // Update stok barang
        $barang = Barang::find($request->id_barang);
        $barang->increment('stok', $request->jumlah);

        return redirect()->back()->with('success', 'Transaksi masuk berhasil disimpan');
    }

    // Admin - Transaksi Keluar (Koreksi Stok)
    public function keluarIndex()
    {
        $barangs = Barang::all();
        $karyawans = Karyawan::all();
        return view('transaksi.keluar', compact('barangs', 'karyawans'));
    }

    public function keluarStore(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'id_karyawan' => 'required|exists:karyawan,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable'
        ]);

        $barang = Barang::find($request->id_barang);
        
        // Cek stok cukup atau tidak
        if($barang->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk transaksi keluar');
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'jenis_transaksi' => 'keluar',
            'id_barang' => $request->id_barang,
            'id_karyawan' => $request->id_karyawan,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        // Update stok barang
        $barang->decrement('stok', $request->jumlah);

        return redirect()->back()->with('success', 'Transaksi keluar berhasil disimpan');
    }

    // Operator - Transaksi Keluar
    public function operatorKeluarIndex()
    {
        $barangs = Barang::all();
        return view('operator.transaksi.keluar', compact('barangs'));
    }

    public function operatorKeluarStore(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable'
        ]);

        $barang = Barang::find($request->id_barang);
        
        // Cek stok cukup atau tidak
        if($barang->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk transaksi keluar');
        }

        // Dapatkan karyawan berdasarkan user yang login
        $karyawan = Auth::user()->karyawan;

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'jenis_transaksi' => 'keluar',
            'id_barang' => $request->id_barang,
            'id_karyawan' => $karyawan->id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        // Update stok barang
        $barang->decrement('stok', $request->jumlah);

        return redirect()->back()->with('success', 'Transaksi keluar berhasil disimpan');
    }

    // Operator - History Transaksi
    public function operatorHistory()
    {
        $karyawan = Auth::user()->karyawan;
        $transaksis = Transaksi::where('id_karyawan', $karyawan->id)
            ->with(['barang', 'karyawan'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        
        return view('operator.history', compact('transaksis'));
    }
}