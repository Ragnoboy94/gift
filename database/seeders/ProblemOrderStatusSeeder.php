<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProblemOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('order_statuses')->insert([
            'name' => 'problem_with_order',
            'display_name' => 'Проблема с заказом',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
