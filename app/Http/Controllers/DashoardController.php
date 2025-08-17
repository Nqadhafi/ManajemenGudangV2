<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $barangHampirHabis = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', today())->count();

        return view('dashboard', compact('totalBarang', 'totalStok', 'barangHampirHabis', 'transaksiHariIni'));
    }
}