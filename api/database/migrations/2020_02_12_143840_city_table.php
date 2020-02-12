<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_CITY_TABLE, function (Blueprint $table) {
            $table->increments(CITY_ID);
            $table->char(COUNTRY_CODE, 2);
            $table->string(CITY_NAME, 100);
            $table->string(DISTRICT_NAME, 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_CITY_TABLE);
    }
}
