<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->integer('mp_payment_id');
            $table->decimal('amount')->default(0.00);
            $table->uuid('order_uuid')->nullable();
            $table->uuid('customer_uuid')->nullable();
            $table->bigInteger('method_id')->unsigned()->nullable();
            $table->bigInteger('status_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_uuid')->references('uuid')->on('orders');
            $table->foreign('customer_uuid')->references('uuid')->on('customers');
            $table->foreign('method_id')->references('id')->on('payment_methods');
            $table->foreign('status_id')->references('id')->on('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
