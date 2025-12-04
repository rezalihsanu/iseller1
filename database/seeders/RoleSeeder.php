<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'owner',
                'description' => 'Full access owner',
                'created_at' => now(),
            ],
            [
                'name' => 'kasir',
                'description' => 'Cashier — can create orders only',
                'created_at' => now(),
            ],
            [
                'name' => 'customer',
                'description' => 'Customer role',
                'created_at' => now(),
            ],
        ]);
    }
}
