<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'Cash',
                'code' => 'CASH',
                'type' => 'cash',
                'provider' => null,
                'configuration' => null,
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'QRIS',
                'code' => 'QRIS',
                'type' => 'online',
                'provider' => 'midtrans',
                'configuration' => null, // isi nanti dari Filament atau ENV
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'GoPay',
                'code' => 'GOPAY',
                'type' => 'online',
                'provider' => 'midtrans',
                'configuration' => null,
                'is_active' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'ShopeePay',
                'code' => 'SHOPEEPAY',
                'type' => 'online',
                'provider' => 'midtrans',
                'configuration' => null,
                'is_active' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'TRANSFER',
                'type' => 'online',
                'provider' => 'midtrans',
                'configuration' => null,
                'is_active' => 0,
                'created_at' => now(),
            ],
        ]);
    }
}
