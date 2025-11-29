<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            [
                'name' => 'Piece',
                'code' => 'PCS',
                'created_at' => now(),
            ],
            [
                'name' => 'Kilogram',
                'code' => 'KG',
                'created_at' => now(),
            ],
            [
                'name' => 'Liter',
                'code' => 'LTR',
                'created_at' => now(),
            ],
        ]);
    }
}
