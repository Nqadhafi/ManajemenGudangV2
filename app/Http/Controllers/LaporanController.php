<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // Laporan Stok
    public function stokIndex(Request $request)
    {
        $barangs = Barang::with(['kategori', 'supplier'])->get();
        return view('laporan.stok', compact('barangs'));
    }

    public function stokPrint(Request $request)
    {
        $barangs = Barang::with(['kategori', 'supplier'])->get();
        return view('laporan.stok-print', compact('barangs'));
    }

    // Laporan Transaksi
    public function transaksiIndex(Request $request)
    {
        $query = Transaksi::with(['barang', 'karyawan']);
        
        if($request->has('periode') && $request->periode) {
            $periode = $request->periode;
            switch($periode) {
                case 'harian':
                    $query->whereDate('tanggal_transaksi', today());
                    break;
                case 'mingguan':
                    $query->whereBetween('tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'bulanan':
                    $query->whereMonth('tanggal_transaksi', now()->month)
                          ->whereYear('tanggal_transaksi', now()->year);
                    break;
                case 'tahunan':
                    $query->whereYear('tanggal_transaksi', now()->year);
                    break;
                case 'custom':
                    if($request->tanggal_awal && $request->tanggal_akhir) {
                        $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
                    }
                    break;
            }
        }
        
        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $periode = $request->periode ?? 'semua';
        
        return view('laporan.transaksi', compact('transaksis', 'periode'));
    }

    public function transaksiPrint(Request $request)
    {
        $query = Transaksi::with(['barang', 'karyawan']);
        
        if($request->has('periode') && $request->periode) {
            $periode = $request->periode;
            switch($periode) {
                case 'harian':
                    $query->whereDate('tanggal_transaksi', today());
                    break;
                case 'mingguan':
                    $query->whereBetween('tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'bulanan':
                    $query->whereMonth('tanggal_transaksi', now()->month)
                          ->whereYear('tanggal_transaksi', now()->year);
                    break;
                case 'tahunan':
                    $query->whereYear('tanggal_transaksi', now()->year);
                    break;
                case 'custom':
                    if($request->tanggal_awal && $request->tanggal_akhir) {
                        $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
                    }
                    break;
            }
        }
        
        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
        $periode = $request->periode ?? 'semua';
        
        return view('laporan.transaksi-print', compact('transaksis', 'periode'));
    }
}