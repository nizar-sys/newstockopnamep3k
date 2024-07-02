<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'atasan',
                'email' => 'atasan@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'petugas',
                'email' => 'petugas@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);
    }
}
