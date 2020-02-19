<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LectureTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_LECTURE_TABLE, function (Blueprint $table) {
            $table->increments(LECTURE_ID);
            $table->string(LECTURE_AREA, 100);
            $table->string(LECTURE_THEME, 100);
            $table->float(AVERAGE_TRY);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_LECTURE_TABLE);
    }
}
