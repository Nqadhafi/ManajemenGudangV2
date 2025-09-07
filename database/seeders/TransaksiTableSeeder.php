<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Karyawan;

class TransaksiTableSeeder extends Seeder
{
    public function run(): void
    {
        // Opsional: kosongkan agar hasil bersih
        DB::table('transaksi')->truncate();

        $awalRentang = Carbon::now()->subDays(45);
        $karyawanIds = Karyawan::pluck('id')->all();

        if (empty($karyawanIds)) {
            $this->command->warn('Tidak ada karyawan untuk referensi transaksi.');
            return;
        }

        $barangs = Barang::all();
        if ($barangs->isEmpty()) {
            $this->command->warn('Barang kosong. Jalankan BarangTableSeeder dulu.');
            return;
        }

        $rows = [];
        $tid = 1;

        foreach ($barangs as $b) {
            // baseline masuk awal berdasarkan jenis bahan baku
            $baseline = $this->baselineAwal($b->nama_barang);

            $tgl = $awalRentang->copy()->addDays(rand(0,5))->setTime(rand(8,17), rand(0,59), 0);
            $kid = $karyawanIds[array_rand($karyawanIds)];
            $stokAkhir = 0;

            $rows[] = [
                'id' => $tid++,
                'jenis_transaksi' => 'masuk',
                'id_barang' => $b->id,
                'id_karyawan' => $kid,
                'jumlah' => $baseline,
                'keterangan' => 'Stok awal pembelian bahan baku',
                'tanggal_transaksi' => $tgl,
                'created_at' => $tgl,
                'updated_at' => $tgl,
            ];
            $stokAkhir += $baseline;

            // 6–8 transaksi tambahan
            $n = rand(6, 8);
            for ($i = 0; $i < $n; $i++) {
                $jenis = (rand(0,1) ? 'masuk' : 'keluar');
                $qty = $this->qtyWajar($b->nama_barang);

                if ($jenis === 'keluar' && ($stokAkhir - $qty) < 0) {
                    $jenis = 'masuk';
                }
                $stokAkhir += ($jenis === 'masuk') ? $qty : -$qty;

                $tgl = $awalRentang->copy()->addDays(rand(6,44))->setTime(rand(8,20), rand(0,59), rand(0,59));
                $kid = $karyawanIds[array_rand($karyawanIds)];
                $ket = $jenis === 'masuk' ? 'Pembelian bahan baku' : 'Pemakaian produksi';

                $rows[] = [
                    'id' => $tid++,
                    'jenis_transaksi' => $jenis,
                    'id_barang' => $b->id,
                    'id_karyawan' => $kid,
                    'jumlah' => $qty,
                    'keterangan' => $ket,
                    'tanggal_transaksi' => $tgl,
                    'created_at' => $tgl,
                    'updated_at' => $tgl,
                ];
            }

            // sinkron stok barang
            DB::table('barang')->where('id', $b->id)->update([
                'stok' => $stokAkhir,
                'updated_at' => now(),
            ]);
        }

        usort($rows, fn($a,$b) => strtotime($a['tanggal_transaksi']) <=> strtotime($b['tanggal_transaksi']));
        DB::table('transaksi')->insert($rows);
    }

    // baseline awal khusus bahan baku percetakan
    protected function baselineAwal(string $nama): int
    {
        $n = strtolower($nama);
        if (str_contains($n, 'kertas')) return rand(80, 200);
        if (str_contains($n, 'vinyl') || str_contains($n, 'stiker')) return rand(120, 300);
        if (str_contains($n, 'flexy') || str_contains($n, 'mmt')) return rand(150, 400);
        if (str_contains($n, 'tinta')) return rand(20, 60);
        if (str_contains($n, 'laminasi')) return rand(8, 25);
        return rand(10, 40);
    }

    // qty per transaksi disesuaikan bahan baku
    protected function qtyWajar(string $nama): int
    {
        $n = strtolower($nama);
        if (str_contains($n, 'kertas')) {
            $ops = [10, 20, 25, 30, 40, 50];
        } elseif (str_contains($n, 'vinyl') || str_contains($n, 'stiker')) {
            $ops = [20, 25, 30, 40, 50, 60, 80];
        } elseif (str_contains($n, 'flexy') || str_contains($n, 'mmt')) {
            $ops = [10, 20, 30, 40, 50, 80, 100]; // meter
        } elseif (str_contains($n, 'tinta')) {
            $ops = [2, 3, 5, 8, 10, 12]; // botol literan
        } elseif (str_contains($n, 'laminasi')) {
            $ops = [1, 2, 3, 5, 7, 10]; // roll
        } else {
            $ops = [1, 2, 3, 5, 7, 10];
        }
        return $ops[array_rand($ops)];
    }
}
