<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        UsersTableSeeder::class,
        SupplierTableSeeder::class,
        KategoriTableSeeder::class,
        DivisiTableSeeder::class,
        KaryawanTableSeeder::class,
        BarangTableSeeder::class,
        TransaksiTableSeeder::class,
        ]);
    }
}