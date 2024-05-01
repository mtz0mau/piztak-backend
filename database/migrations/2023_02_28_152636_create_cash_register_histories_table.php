<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_histories', function (Blueprint $table) {
            $table->id();
            $table->double('amount')->default(0);
            $table->double('previous_amount')->default(0);
            $table->enum('action', ['add', 'subtract']);
            $table->text('description')->nullable();
            $table->foreignId('cash_register_id')->nullable();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('SET NULL');
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_register_histories');
    }
};
