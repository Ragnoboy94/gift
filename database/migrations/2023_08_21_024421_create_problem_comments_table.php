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
        Schema::create('problem_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_problem_id');
            $table->text('comment');
            $table->unsignedBigInteger('resolved_by');
            $table->timestamps();

            $table->foreign('order_problem_id')->references('id')->on('order_problems')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_comments');
    }
};
