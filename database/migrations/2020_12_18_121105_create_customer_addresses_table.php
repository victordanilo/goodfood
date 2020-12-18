<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('street')->nullable();
            $table->string('n')->nullable();
            $table->string('complement')->nullable();
            $table->string('district')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('uf')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
