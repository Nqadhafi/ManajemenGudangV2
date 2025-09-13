<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
public function index()
{
    $totalBarang = Barang::count();
    $totalStok = Barang::sum('stok');
    $barangHampirHabis = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
    $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', today())->count();

    // Total aset hanya untuk barang yang sudah punya avg_harga (tidak null)
    $totalNilaiAset = Barang::whereNotNull('avg_harga')
        ->selectRaw('SUM(avg_harga * stok) as total')
        ->value('total') ?? 0;

    return view('dashboard', compact(
        'totalBarang',
        'totalStok',
        'barangHampirHabis',
        'transaksiHariIni',
        'totalNilaiAset'          // << kirim ke view
    ));
}
}