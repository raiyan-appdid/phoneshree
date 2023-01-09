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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('customer_pic')->nullable();
            $table->string('imei_number')->nullable();
            $table->string('customer_buy_price')->nullable();
            $table->string('product_title')->nullable();
            $table->text('product_description')->nullable();
            $table->string('product_selling_price')->nullable();
            $table->string('sold_to_customer_name')->nullable();
            $table->string('sold_to_customer_number')->nullable();
            $table->string('product_sold_price')->nullable();
            $table->enum('status', ['inventory', 'livesell', 'sold'])->default('inventory');
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
        Schema::dropIfExists('products');
    }
};
