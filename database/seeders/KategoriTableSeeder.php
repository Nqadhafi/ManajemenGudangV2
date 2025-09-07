<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriTableSeeder extends Seeder
{
    public function run()
    {
        // Semua kategori difokuskan ke bahan baku percetakan
        $kategoris = [
            ['nama' => 'Kertas (Bahan Baku)'],
            ['nama' => 'Stiker Vinyl (Bahan Baku)'],
            ['nama' => 'Flexy / MMT (Bahan Baku)'],
            ['nama' => 'Tinta (Bahan Baku)'],
            ['nama' => 'Laminasi (Bahan Baku)'],
        ];

        Kategori::query()->delete(); // opsional, agar rapi saat reseed
        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
