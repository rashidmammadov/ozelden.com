<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuitableLocationTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_SUITABLE_LOCATION_TABLE, function (Blueprint $table) {
            $table->integer(TUTOR_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->boolean(STUDENT_HOME);
            $table->boolean(TUTOR_HOME);
            $table->boolean(ETUDE);
            $table->boolean(COURSE);
            $table->boolean(LIBRARY);
            $table->boolean(OVER_INTERNET);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SUITABLE_LOCATION_TABLE);
    }

}
