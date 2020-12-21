<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('company_uuid')->nullable();
            $table->uuid('customer_uuid')->nullable();
            $table->decimal('amount')->default(0.00);
            $table->decimal('delivery_price')->default(0.00);
            $table->string('delivery_address')->nullable();
            $table->bigInteger('status_id')->unsigned();
            $table->string('obs')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_uuid')->references('uuid')->on('companies');
            $table->foreign('customer_uuid')->references('uuid')->on('customers');
            $table->foreign('status_id')->references('id')->on('order_status');
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
}
