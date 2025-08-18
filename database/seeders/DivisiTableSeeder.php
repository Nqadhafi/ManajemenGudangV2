<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisis = [
            ['nama' => 'Mesin Ricoh'],
            ['nama' => 'Mesin MMT'],
            ['nama' => 'Mesin Potong'],
            ['nama' => 'Mesin Offset'],
            ['nama' => 'Mesin Photo']
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }
    }
}