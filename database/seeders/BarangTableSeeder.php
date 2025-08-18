<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;

class BarangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();

        if ($kategoris->count() > 0 && $suppliers->count() > 0) {
            $barangs = [
                [
                    'nama_barang' => 'Laptop Dell Inspiron',
                    'stok' => 15,
                    'satuan' => 'unit',
                    'stok_minimum' => 5,
                    'id_kategori' => $kategoris[0]->id, // Elektronik
                    'id_supplier' => $suppliers[0]->id  // PT. Sumber Makmur
                ],
                [
                    'nama_barang' => 'Kertas HVS A4 70gsm',
                    'stok' => 500,
                    'satuan' => 'rim',
                    'stok_minimum' => 100,
                    'id_kategori' => $kategoris[1]->id, // Peralatan Kantor
                    'id_supplier' => $suppliers[1]->id  // CV. Berkah Jaya
                ],
                [
                    'nama_barang' => 'Pulpen Pilot Hitam',
                    'stok' => 200,
                    'satuan' => 'buah',
                    'stok_minimum' => 50,
                    'id_kategori' => $kategoris[3]->id, // Alat Tulis
                    'id_supplier' => $suppliers[2]->id  // Toko Elektronik Sejahtera
                ],
                [
                    'nama_barang' => 'Meja Kantor Kayu',
                    'stok' => 10,
                    'satuan' => 'unit',
                    'stok_minimum' => 3,
                    'id_kategori' => $kategoris[4]->id, // Furniture
                    'id_supplier' => $suppliers[3]->id  // Distributor Peralatan Kantor
                ],
                [
                    'nama_barang' => 'Bahan Baku Plastik',
                    'stok' => 1000,
                    'satuan' => 'kg',
                    'stok_minimum' => 200,
                    'id_kategori' => $kategoris[2]->id, // Bahan Baku
                    'id_supplier' => $suppliers[4]->id  // Supplier Bahan Baku
                ],
                [
                    'nama_barang' => 'Monitor LCD 24 inch',
                    'stok' => 8,
                    'satuan' => 'unit',
                    'stok_minimum' => 3,
                    'id_kategori' => $kategoris[0]->id, // Elektronik
                    'id_supplier' => $suppliers[0]->id  // PT. Sumber Makmur
                ],
                [
                    'nama_barang' => 'Staples Max',
                    'stok' => 150,
                    'satuan' => 'box',
                    'stok_minimum' => 30,
                    'id_kategori' => $kategoris[3]->id, // Alat Tulis
                    'id_supplier' => $suppliers[1]->id  // CV. Berkah Jaya
                ],
                [
                    'nama_barang' => 'Kursi Kantor Ergonomis',
                    'stok' => 12,
                    'satuan' => 'unit',
                    'stok_minimum' => 5,
                    'id_kategori' => $kategoris[4]->id, // Furniture
                    'id_supplier' => $suppliers[3]->id  // Distributor Peralatan Kantor
                ]
            ];

            foreach ($barangs as $barang) {
                Barang::create($barang);
            }
        }
    }
}