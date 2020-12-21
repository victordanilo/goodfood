<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_reviews', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('review');
            $table->float('rate', 8, 2);
            $table->uuid('customer_uuid')->nullable();
            $table->uuid('company_uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_uuid')->references('uuid')->on('companies');
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
        Schema::dropIfExists('company_reviews');
    }
}
