<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi;

class KaryawanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users without karyawan
        $users = User::whereDoesntHave('karyawan')->get();
        $divisis = Divisi::all();

        if ($users->count() >= 2 && $divisis->count() >= 3) {
            // Assign first user as admin karyawan
            Karyawan::create([
                'id_user' => $users[0]->id,
                'id_divisi' => $divisis[0]->id, // Gudang
                'nama' => 'Admin Gudang',
                'nik' => 'EMP001',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'nomor_hp' => '081111111111'
            ]);

            // Assign second user as operator karyawan
            Karyawan::create([
                'id_user' => $users[1]->id,
                'id_divisi' => $divisis[0]->id, // Gudang
                'nama' => 'Operator Gudang',
                'nik' => 'EMP002',
                'alamat' => 'Jl. Jenderal No. 2, Jakarta',
                'nomor_hp' => '082222222222'
            ]);

            // Create additional karyawan
            $additionalKaryawan = [
                [
                    'id_user' => $users->count() > 2 ? $users[2]->id : null,
                    'id_divisi' => $divisis[1]->id, // Administrasi
                    'nama' => 'Staff Administrasi',
                    'nik' => 'EMP003',
                    'alamat' => 'Jl. Sudirman No. 3, Jakarta',
                    'nomor_hp' => '083333333333'
                ],
                [
                    'id_user' => $users->count() > 3 ? $users[3]->id : null,
                    'id_divisi' => $divisis[2]->id, // Pembelian
                    'nama' => 'Staff Pembelian',
                    'nik' => 'EMP004',
                    'alamat' => 'Jl. Gatot Subroto No. 4, Jakarta',
                    'nomor_hp' => '084444444444'
                ]
            ];

            foreach ($additionalKaryawan as $karyawan) {
                // Only create if user exists
                if ($karyawan['id_user']) {
                    Karyawan::create($karyawan);
                }
            }
        }
    }
}