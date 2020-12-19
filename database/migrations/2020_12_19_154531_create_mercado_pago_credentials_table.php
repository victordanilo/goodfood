<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMercadoPagoCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercado_pago_credentials', function (Blueprint $table) {
            $table->bigInteger('company_id')->unsigned()->primary();
            $table->integer('mp_user_id');
            $table->string('public_key');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->integer('expires_in');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mercado_pago_credentials');
    }
}
