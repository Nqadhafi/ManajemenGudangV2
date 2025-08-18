<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'nama_supplier' => 'PT. Sumber Kertas',
                'no_hp_supplier' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta'
            ],
            [
                'nama_supplier' => 'CV. Berkah Art Paper',
                'no_hp_supplier' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 456, Bandung'
            ],
            [
                'nama_supplier' => 'Toko Flexy Murah',
                'no_hp_supplier' => '083456789012',
                'alamat' => 'Jl. Gatot Subroto No. 789, Surabaya'
            ],
            [
                'nama_supplier' => 'Indoplast ',
                'no_hp_supplier' => '084567890123',
                'alamat' => 'Jl. Diponegoro No. 321, Semarang'
            ],
            [
                'nama_supplier' => 'Duplex Sejahtera',
                'no_hp_supplier' => '085678901234',
                'alamat' => 'Jl. Ahmad Yani No. 654, Medan'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}