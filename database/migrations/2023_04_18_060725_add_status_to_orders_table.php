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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->default(1)->after('deadline'); // 1 - это ID статуса "active"
            $table->foreign('status_id')->references('id')->on('order_statuses')->onDelete('restrict');
            $table->unsignedBigInteger('elf_id')->nullable()->after('status_id');
            $table->foreign('elf_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['elf_id']);
            $table->dropColumn('elf_id');
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
