<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('order_uuid')->nullable();
            $table->uuid('product_uuid')->nullable();
            $table->integer('qty');
            $table->decimal('price')->default(0.00);
            $table->timestamps();

            $table->foreign('order_uuid')->references('uuid')->on('orders');
            $table->foreign('product_uuid')->references('uuid')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
