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
        Schema::create('razorpay_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->string('amount')->nullable();
            $table->enum('status', ['created', 'inserted', 'failed'])->default('created')->nullable();
            $table->enum('type', ['load_wallet', 'membership'])->nullable();
            $table->json('membership_data')->nullable();
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
        Schema::dropIfExists('razorpay_orders');
    }
};
