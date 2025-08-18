<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoris = [
            ['nama' => 'Bahan Khusus'],
            ['nama' => 'Kertas A3'],
            ['nama' => 'Stiker A3'],
            ['nama' => 'Flexy MMT'],
            ['nama' => 'Kertas Plano']
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}