<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin User
        User::create([
            'email' => 'admin@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Operator User
        User::create([
            'email' => 'operator@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'operator'
        ]);

        // Additional Users for testing
        User::factory()->count(5)->create();
    }
}