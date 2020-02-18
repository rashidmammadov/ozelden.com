<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuitableFacilityTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_SUITABLE_FACILITY_TABLE, function (Blueprint $table) {
            $table->integer(TUTOR_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->boolean(DEMO);
            $table->boolean(GROUP_DISCOUNT);
            $table->boolean(PACKAGE_DISCOUNT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SUITABLE_FACILITY_TABLE);
    }
}
