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
        Schema::table('role_user', function (Blueprint $table) {
            $table->float('rating')->default(1.0);
        });
    }

    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
