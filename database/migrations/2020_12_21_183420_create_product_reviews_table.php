<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('review');
            $table->float('rate', 8, 2);
            $table->uuid('product_uuid')->nullable();
            $table->uuid('customer_uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_uuid')->references('uuid')->on('products');
            $table->foreign('customer_uuid')->references('uuid')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
}
