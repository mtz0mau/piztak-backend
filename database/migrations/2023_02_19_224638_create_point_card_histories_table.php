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
        Schema::create('point_card_histories', function (Blueprint $table) {
            $table->id();
            $table->double('amount')->default(0);
            $table->double('previous_amount')->default(0);
            $table->enum('action', ['add', 'subtract']);
            $table->text('description')->nullable();
            $table->foreignId('point_card_id')->references('id')->on('point_cards');
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
        Schema::dropIfExists('point_card_histories');
    }
};
