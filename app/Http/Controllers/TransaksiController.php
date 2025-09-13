<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'id_barang'     => 'required|exists:barang,id',
        'id_karyawan'   => 'required|exists:karyawan,id',
        'jumlah'        => 'required|integer|min:1',
        'harga_satuan'  => 'required|numeric|min:0', // harga wajib saat MASUK
        'keterangan'    => 'nullable|string',
    ]);

    DB::transaction(function () use ($request) {
        $barang = \App\Models\Barang::lockForUpdate()->findOrFail($request->id_barang);

        $qty   = (int) $request->jumlah;
        $price = (float) $request->harga_satuan;

        // 1) Catat transaksi MASUK + harga
        \App\Models\Transaksi::create([
            'jenis_transaksi' => 'masuk',
            'id_barang'       => $barang->id,
            'id_karyawan'     => $request->id_karyawan,
            'jumlah'          => $qty,
            'keterangan'      => $request->keterangan,
            'harga_satuan'    => $price,
        ]);

        // 2) Update stok
        $stokBaru = (int)$barang->stok + $qty;

        // 3) Update agregat "rata-rata per transaksi"
        $sum  = (float)$barang->sum_harga_masuk + $price;
        $cnt  = (int)$barang->cnt_harga_masuk + 1;
        $avg  = $cnt > 0 ? $sum / $cnt : null; // null jika belum ada transaksi berharga

        // 4) Commit ke barang
        $barang->update([
            'stok'             => $stokBaru,
            'sum_harga_masuk'  => $sum,
            'cnt_harga_masuk'  => $cnt,
            'avg_harga'        => $avg, // cache untuk tampilan cepat
        ]);
    });

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
        
        return view('operator.transaksi.history', compact('transaksis'));
    }
}