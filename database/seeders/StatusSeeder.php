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
            ['name' => 'active', 'display_name' => 'Активный', 'display_name_en' => 'Active'],
            ['name' => 'in_progress', 'display_name' => 'В процессе', 'display_name_en' => 'In progress'],
            ['name' => 'ready_for_delivery', 'display_name' => 'Готов к доставке', 'display_name_en' => 'Ready for delivery'],
            ['name' => 'cancelled_by_customer', 'display_name' => 'Отменен клиентом', 'display_name_en' => 'Cancelled by customer'],
            ['name' => 'cancelled_by_elf', 'display_name' => 'Отменен исполнителем', 'display_name_en' => 'Cancelled by Elf'],
            ['name' => 'created', 'display_name' => 'Создан', 'display_name_en' => 'Created'],
            ['name' => 'finished', 'display_name' => 'Завершен', 'display_name_en' => 'Finished'],
        ];

        foreach ($status as $stat) {
            DB::table('order_statuses')->insert([
                'name' => $stat['name'],
                'display_name' => $stat['display_name'],
                'display_name_en' => $stat['display_name_en'],
            ]);
        }
    }
}
