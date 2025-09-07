<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;

class BarangTableSeeder extends Seeder
{
    public function run()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();

        if ($kategoris->count() < 5 || $suppliers->count() < 1) {
            $this->command->warn('Kategori/Supplier belum siap. Jalankan Supplier & Kategori seeder dulu.');
            return;
        }

        // Indeks kategori (sesuaikan dengan urutan di KategoriTableSeeder di atas)
        $katKertas   = $kategoris[0]->id;
        $katVinyl    = $kategoris[1]->id;
        $katFlexy    = $kategoris[2]->id;
        $katTinta    = $kategoris[3]->id;
        $katLaminasi = $kategoris[4]->id;

        // Ambil supplier secara melingkar
        $sid = fn($i) => $suppliers[$i % $suppliers->count()]->id;

        $barangs = [
            // Kertas
            ['nama_barang' => 'Kertas HVS A4 80gsm',          'stok' => 0, 'satuan' => 'Rim',   'stok_minimum' => 50,  'id_kategori' => $katKertas,   'id_supplier' => $sid(0)],
            ['nama_barang' => 'Art Paper A3 150gsm',          'stok' => 0, 'satuan' => 'Rim',   'stok_minimum' => 30,  'id_kategori' => $katKertas,   'id_supplier' => $sid(1)],
            ['nama_barang' => 'Ivory A3 260gsm',               'stok' => 0, 'satuan' => 'Rim',   'stok_minimum' => 20,  'id_kategori' => $katKertas,   'id_supplier' => $sid(2)],

            // Stiker Vinyl
            ['nama_barang' => 'Stiker Vinyl Glossy A3+',      'stok' => 0, 'satuan' => 'Lembar','stok_minimum' => 100, 'id_kategori' => $katVinyl,    'id_supplier' => $sid(1)],
            ['nama_barang' => 'Stiker Vinyl Doff A3+',        'stok' => 0, 'satuan' => 'Lembar','stok_minimum' => 100, 'id_kategori' => $katVinyl,    'id_supplier' => $sid(2)],

            // Flexy/MMT
            ['nama_barang' => 'Flexy China 280gsm (3.2m)',    'stok' => 0, 'satuan' => 'Meter', 'stok_minimum' => 150, 'id_kategori' => $katFlexy,    'id_supplier' => $sid(3)],
            ['nama_barang' => 'Flexy Korea 440gsm (3.2m)',    'stok' => 0, 'satuan' => 'Meter', 'stok_minimum' => 120, 'id_kategori' => $katFlexy,    'id_supplier' => $sid(4)],

            // Tinta (CMYK)
            ['nama_barang' => 'Tinta Hitam (K) 1 Liter',      'stok' => 0, 'satuan' => 'Botol', 'stok_minimum' => 20,  'id_kategori' => $katTinta,    'id_supplier' => $sid(0)],
            ['nama_barang' => 'Tinta Cyan (C) 1 Liter',       'stok' => 0, 'satuan' => 'Botol', 'stok_minimum' => 15,  'id_kategori' => $katTinta,    'id_supplier' => $sid(0)],
            ['nama_barang' => 'Tinta Magenta (M) 1 Liter',    'stok' => 0, 'satuan' => 'Botol', 'stok_minimum' => 15,  'id_kategori' => $katTinta,    'id_supplier' => $sid(0)],
            ['nama_barang' => 'Tinta Yellow (Y) 1 Liter',     'stok' => 0, 'satuan' => 'Botol', 'stok_minimum' => 15,  'id_kategori' => $katTinta,    'id_supplier' => $sid(0)],

            // Laminasi
            ['nama_barang' => 'Laminating Glossy 32 micron',  'stok' => 0, 'satuan' => 'Roll',  'stok_minimum' => 10,  'id_kategori' => $katLaminasi, 'id_supplier' => $sid(2)],
            ['nama_barang' => 'Laminating Doff 32 micron',    'stok' => 0, 'satuan' => 'Roll',  'stok_minimum' => 10,  'id_kategori' => $katLaminasi, 'id_supplier' => $sid(2)],
        ];

        Barang::query()->delete(); // opsional, agar rapi saat reseed
        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
