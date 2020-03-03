<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AverageTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_AVERAGE_TABLE, function (Blueprint $table) {
            $table->integer(USER_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->double(RANKING_AVG)->nullable();
            $table->double(EXPERIENCE_AVG)->nullable();
            $table->double(PRICE_AVG)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_AVERAGE_TABLE);
    }
}
