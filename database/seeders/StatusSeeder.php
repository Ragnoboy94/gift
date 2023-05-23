<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            ['name' => 'active', 'display_name' => 'Активный'],
            ['name' => 'in_progress', 'display_name' => 'В процессе'],
            ['name' => 'ready_for_delivery', 'display_name' => 'Готов к доставке'],
            ['name' => 'cancelled_by_customer', 'display_name' => 'Отменен клиентом'],
            ['name' => 'cancelled_by_elf', 'display_name' => 'Отменен исполнителем'],
            ['name' => 'created', 'display_name' => 'Создан'],
            ['name' => 'finished', 'display_name' => 'Завершен'],
        ];

        foreach ($status as $stat) {
            DB::table('order_statuses')->insert([
                'name' => $stat['name'],
                'display_name' => $stat['display_name'],
            ]);
        }
    }
}
