<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsAddressToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('lat')->nullable()->after('level');
            $table->string('lng')->nullable()->after('lat');
            $table->string('street')->nullable()->after('lng');
            $table->string('n')->nullable()->after('street');
            $table->string('complement')->nullable()->after('n');
            $table->string('district')->nullable()->after('complement');
            $table->string('zip_code')->nullable()->after('district');
            $table->string('city')->nullable()->after('zip_code');
            $table->string('uf')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'lat',
                'lng',
                'street',
                'n',
                'complement',
                'district',
                'zip_code',
                'city',
                'uf',
            ]);
        });
    }
}
