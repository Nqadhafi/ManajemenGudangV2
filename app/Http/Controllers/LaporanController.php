<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Kategori;
use App\Models\Supplier;

class LaporanController extends Controller
{
    // Laporan Stok
public function stokIndex(Request $request)
{
    $barangs   = Barang::with(['kategori', 'supplier'])
                  ->orderBy('nama_barang')
                  ->get();
    $kategoris = Kategori::orderBy('nama')->get(['id','nama']);
    $suppliers = Supplier::orderBy('nama_supplier')->get(['id','nama_supplier']);

    return view('laporan.stok', compact('barangs','kategoris','suppliers'));
}

public function stokSearch(Request $request)
{
    $q          = trim((string)$request->query('q', ''));       // hanya nama
    $kategoriId = $request->query('kategori_id');
    $supplierId = $request->query('supplier_id');

    $barangs = Barang::with(['kategori','supplier'])
        ->when($q !== '', fn($qry) => $qry->where('nama_barang', 'like', "%{$q}%"))
        ->when($kategoriId, fn($qry, $id) => $qry->where('id_kategori', $id))
        ->when($supplierId, fn($qry, $id) => $qry->where('id_supplier', $id))
        ->orderBy('nama_barang')
        ->limit(500) // batasi agar tetap ringan
        ->get();

    // hitung total aset hasil filter
    $totalAset = 0;
    foreach ($barangs as $b) {
        $avg = $b->avg_harga ?? null;
        if (!is_null($avg)) $totalAset += ($avg * $b->stok);
    }

    return response()->json([
        'rows'            => view('laporan._stok_rows', compact('barangs'))->render(),
        'total'           => $totalAset,
        'total_formatted' => 'Rp ' . number_format($totalAset, 2, ',', '.'),
    ]);
}


public function stokPrint(Request $request)
{
    $q          = trim((string) $request->query('q', ''));  // hanya nama barang
    $kategoriId = $request->query('kategori_id');
    $supplierId = $request->query('supplier_id');

    $barangs = Barang::with(['kategori', 'supplier'])
        ->when($q !== '', fn($qry) => $qry->where('nama_barang', 'like', "%{$q}%"))
        ->when($kategoriId, fn($qry, $id) => $qry->where('id_kategori', $id))
        ->when($supplierId, fn($qry, $id) => $qry->where('id_supplier', $id))
        ->orderBy('nama_barang')
        ->get();

    // (Opsional) kalau template print butuh total siap-pakai, bisa hitung di sini:
    // $totalAset = 0;
    // foreach ($barangs as $b) {
    //     $avg = $b->avg_harga ?? null;
    //     if (!is_null($avg)) $totalAset += ($avg * $b->stok);
    // }
    // return view('laporan.stok-print', compact('barangs', 'totalAset'));

    return view('laporan.stok-print', compact('barangs'));
}


    
    // Laporan Transaksi
public function transaksiIndex(Request $request)
{
    $query = Transaksi::with(['barang', 'karyawan']);

    // ---- FILTER PERIODE ----
    if ($request->filled('periode')) {
        switch ($request->periode) {
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
                if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                    $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
                }
                break;
        }
    }

    // ---- FILTER TAMBAHAN ----
    if ($request->filled('jenis')) {
        $query->where('jenis_transaksi', $request->jenis); // 'masuk' / 'keluar'
    }
    if ($request->filled('barang_id')) {
        $query->where('id_barang', $request->barang_id);
    }
    if ($request->filled('karyawan_id')) {
        $query->where('id_karyawan', $request->karyawan_id);
    }

    $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
    $periode = $request->periode ?? 'semua';

    // Data untuk dropdown filter
    $barangsList   = Barang::orderBy('nama_barang')->get(['id','nama_barang']);
    $karyawansList = Karyawan::orderBy('nama')->get(['id','nama']);

    return view('laporan.transaksi', compact('transaksis', 'periode', 'barangsList', 'karyawansList'));
}

public function transaksiPrint(Request $request)
{
    $query = Transaksi::with(['barang', 'karyawan']);

    // ---- FILTER PERIODE ----
    if ($request->filled('periode')) {
        switch ($request->periode) {
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
                if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
                    $query->whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir]);
                }
                break;
        }
    }

    // ---- FILTER TAMBAHAN ----
    if ($request->filled('jenis')) {
        $query->where('jenis_transaksi', $request->jenis);
    }
    if ($request->filled('barang_id')) {
        $query->where('id_barang', $request->barang_id);
    }
    if ($request->filled('karyawan_id')) {
        $query->where('id_karyawan', $request->karyawan_id);
    }

    $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
    $periode = $request->periode ?? 'semua';

    return view('laporan.transaksi-print', compact('transaksis', 'periode'));
}
}