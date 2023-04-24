<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->timestamps();
        });

        // Заполнение таблицы статусами заказов
        \Illuminate\Support\Facades\DB::table('order_statuses')->insert([
            ['name' => 'active', 'display_name' => 'Активный'],
            ['name' => 'in_progress', 'display_name' => 'В процессе'],
            ['name' => 'ready_for_delivery', 'display_name' => 'Готов к доставке'],
            ['name' => 'cancelled_by_customer', 'display_name' => 'Отменено клиентом'],
            ['name' => 'cancelled_by_elf', 'display_name' => 'Отменено исполнителем'],
            ['name' => 'created', 'display_name' => 'Создан'],
            ['name' => 'finished', 'display_name' => 'Завершен'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
};
