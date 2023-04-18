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
            $table->timestamps();
        });

        // Заполнение таблицы статусами заказов
        \Illuminate\Support\Facades\DB::table('order_statuses')->insert([
            ['name' => 'active'],
            ['name' => 'in_progress'],
            ['name' => 'ready_for_delivery'],
            ['name' => 'cancelled_by_customer'],
            ['name' => 'cancelled_by_elf'],
            ['name' => 'created'],
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
