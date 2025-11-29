<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => 1, // owner
                'name' => 'Admin',
                'email' => 'admin@cc.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
            ],
        ]);
    }
}
