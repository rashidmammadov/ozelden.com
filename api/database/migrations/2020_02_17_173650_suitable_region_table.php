<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuitableRegionTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_SUITABLE_REGION_TABLE, function (Blueprint $table) {
            $table->integer(TUTOR_ID);
            $table->string(CITY, 20);
            $table->string(DISTRICT, 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SUITABLE_REGION_TABLE);
    }
}
