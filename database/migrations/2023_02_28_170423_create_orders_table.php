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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamp('delivery_time')->nullable();
            $table->enum('order_status', [
                'registered',
                'prepared',
                'delivery',
                'delivered',
                'canceled',
                'mishap'
            ])->default('registered');
            $table->text('coments')->nullable();
            $table->double('delivery_price')->default(0);
            $table->foreignId('delivery_option_id')->nullable();
            $table->foreign('delivery_option_id')->references('id')->on('delivery_options')->onDelete('SET NULL');
            $table->foreignId('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('SET NULL');
            $table->foreignId('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('SET NULL');
            $table->foreignId('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('SET NULL');
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
        Schema::dropIfExists('orders');
    }
};
