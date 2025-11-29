<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'code' => 'FREE',
                'name' => 'Free Plan',
                'price' => 0,
                'duration_days' => 3650, // 10 tahun
                'created_at' => now(),
            ],
            [
                'code' => 'PREMIUM_MONTHLY',
                'name' => 'Premium Bulanan',
                'price' => 49000,
                'duration_days' => 30,
                'created_at' => now(),
            ],
            [
                'code' => 'PREMIUM_YEARLY',
                'name' => 'Premium Tahunan',
                'price' => 490000,
                'duration_days' => 365,
                'created_at' => now(),
            ],
        ]);
    }
}
