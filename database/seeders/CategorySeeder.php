<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('categories')->insert([
            [
                'name' => 'Makanan',
                'code' => 'MKN',
                'is_active' => 1,
                'description' => 'Kategori makanan',
                'created_at' => now(),
            ],
            [
                'name' => 'Minuman',
                'code' => 'MNM',
                'is_active' => 1,
                'description' => 'Kategori minuman',
                'created_at' => now(),
            ],
            [
                'name' => 'Jasa',
                'code' => 'JSA',
                'is_active' => 1,
                'description' => 'Kategori layanan/jasa',
                'created_at' => now(),
            ],
        ]);
    }
}
